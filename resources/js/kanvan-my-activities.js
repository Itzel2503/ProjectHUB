import Sortable from 'sortablejs';

function initializeSortableJS() {

    // Selecciona todos los contenedores de tareas
    const taskContainers = document.querySelectorAll('.task-container');

    taskContainers.forEach(container => {
        new Sortable(container, {
            group: 'tasks', // Permite arrastrar elementos entre contenedores
            animation: 150, // Duración de la animación
            ghostClass: 'sortable-ghost', // Clase CSS para el elemento fantasma
            chosenClass: 'sortable-chosen', // Clase CSS para el elemento seleccionado
            dragClass: 'sortable-drag', // Clase CSS para el elemento arrastrado
            filter: '.title-container, .title-proyect', // Excluir elementos con la clase title-container
            draggable: '.task-item', // Solo los elementos con la clase .task-item se pueden arrastrar
            onEnd: function (evt) {
                // Obtener el ID del elemento arrastrado
                const taskId = evt.item.id.replace('task-', '');

                // Obtener el contenedor de destino
                const targetContainer = evt.to;

                // Buscar el elemento que contiene la fecha dentro del contenedor de destino
                const fechaElement = targetContainer.querySelector('.title-container p');
                const nuevaFecha = fechaElement ? fechaElement.textContent.trim() : null;

                // Determinar si es un report o una activity
                const isActivity = evt.item.classList.contains('activity'); // Verifica si tiene la clase 'activity'
                const isReport = evt.item.classList.contains('report'); // Verifica si tiene la clase 'report'

                let type = '';
                if (isActivity) {
                    type = 'activity';
                } else if (isReport) {
                    type = 'report';
                }
                
                // Emitir el evento a Livewire
                if (nuevaFecha === 'Atrasados') {
                    // Revertir el movimiento
                    evt.from.insertBefore(evt.item, evt.from.children[evt.oldIndex]);
                    
                    // Mostrar un mensaje de error al usuario
                    Livewire.emitTo('activities-reports.my-activities', 'showError', {
                        message: 'No es posible realizar el movimiento solicitado. Por favor, edite el evento manualmente haciendo clic sobre él".'
                    });
                } else {
                    Livewire.emitTo('activities-reports.my-activities', 'taskMoved', {
                        taskId: taskId,
                        nuevaFecha: nuevaFecha, // Envía la nueva fecha
                        type: type // Envía el tipo (activity o report)
                    });
                }
            },
        });
    });
}

// Escuchar el evento de Livewire para inicializar los botones
window.addEventListener('initializeSortableJS', initializeSortableJS);

// Inicializar los botones cuando la página se carga
document.addEventListener('DOMContentLoaded', initializeSortableJS);
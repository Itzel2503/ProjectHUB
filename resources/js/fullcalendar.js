import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import esLocale from '@fullcalendar/core/locales/es';

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var loadingPage = document.querySelector('.loadingspinner').parentElement; // Selecciona el contenedor de la animación

    // Obtén los datos de las notas
    var eventos = notas.map(function(nota) {
        // Convierte start_date y end_date a objetos Date
        var fechaInicio = new Date(nota.start_date);
        var fechaFin = new Date(nota.end_date);

        // Si las fechas no son válidas, establece valores predeterminados
        if (isNaN(fechaInicio)) {
            console.warn(`Fecha de inicio inválida para la nota ID: ${nota.id}`);
            fechaInicio = new Date(); // Usa la fecha actual como fallback
        }
        if (isNaN(fechaFin)) {
            console.warn(`Fecha de fin inválida para la nota ID: ${nota.id}`);
            fechaFin = fechaInicio; // Usa la fecha de inicio como fallback
        }

        // Determina si el evento es de todo el día
        var esTodoElDia = fechaInicio.getHours() === 0 &&
                        fechaInicio.getMinutes() === 0 &&
                        fechaInicio.getSeconds() === 0 &&
                        fechaFin.getHours() === 0 &&
                        fechaFin.getMinutes() === 0 &&
                        fechaFin.getSeconds() === 0;
        if (esTodoElDia) {
            // Aumenta la fecha final para incluir todo el día (si falta tiempo)
            fechaFin.setDate(fechaFin.getDate() + 1);
        }
        
        var evento = {
            id: nota.id, // Asegúrate de incluir el ID de la nota
            project_name: nota.project_name, // Proyecto nombre
            project_id: nota.project_id, // Proyecto id
            user_name: nota.user_name, // Usuario
            color: nota.color, // Color del evento
            icon: nota.icon, // Icono del evento
            title: nota.title, // Título del evento
            priority: nota.priority, // Prioridad
            start: fechaInicio.toISOString(), // Fecha y hora de inicio del evento
            end: fechaFin.toISOString(), // Fecha y hora de finalización del evento
            deadline: nota.deadline, // Fecha limite del evento
            status: nota.status,  // Estatus del evento
            allDay: esTodoElDia, // Marca el evento como de todo el día o no
            repeat: nota.repeat, // Repetir
            created_at: nota.created_at, // Creada
            updated_at: nota.updated_at, // Actualizada
            delegates_name: nota.delegates.map(delegate => delegate.name).join(', '), // Delegados nombre
            delegates_id: nota.delegates.map(delegate => delegate.id), // Delegados id
            textColor: nota.color === '#facc15' ? 'black' : 'white' // Cambia el color del texto según el color del fondo
        };
        
        return evento;
    });

    var calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin], // Uso de plugins
        initialView: 'timeGridWeek', // Vista inicial
        headerToolbar: {
            left: 'prev,next,todayButton',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        customButtons: {
            todayButton: {
                text: 'Ir a hoy', // Texto del botón
                click: function () {
                    calendar.today(); // Navega a la fecha actual
                },
            },
        },
        locale: esLocale, // Configura el idioma en español
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día'
        },
        allDayText: 'Todo el día',
        noEventsText: 'No hay eventos para mostrar',
        events: eventos, // Agrega los eventos al calendario
        editable: true, // Habilita la edición de eventos (arrastrar y soltar)
        // Limitar el rango de horas visibles (7:00 AM a 9:00 PM)
        slotMinTime: '07:00:00', // Hora mínima
        slotMaxTime: '21:00:00', // Hora máxima
        dateClick: function(info) { // Evento al hacer clic en un día
            // Obtener la fecha seleccionada
            var selectedDate = info.dateStr; // Formato YYYY-MM-DD o YYYY-MM-DDTHH:mm:ssZ

            // Llenar los inputs de fecha con la fecha seleccionada
            document.getElementById('dateFirst').value = selectedDate.split('T')[0]; // Extraer solo la fecha (YYYY-MM-DD)
            document.getElementById('dateSecond').value = selectedDate.split('T')[0]; // Extraer solo la fecha (YYYY-MM-DD)

            // Obtener referencias a los elementos del formulario
            let starTime = document.getElementById('starTime');
            let endTime = document.getElementById('endTime');
            let allDayCheckbox = document.getElementById('allDay');

            // Configurar el comportamiento inicial
            allDayCheckbox.checked = true; // Marcar el checkbox "Todo el día"
            starTime.value = ''; // Limpiar el campo de hora de inicio
            endTime.value = '';  // Limpiar el campo de hora de finalización
            starTime.disabled = true; // Deshabilitar el campo de hora de inicio
            endTime.disabled = true;  // Deshabilitar el campo de hora de finalización

            // Verificar si selectedDate incluye hora y minutos
            if (selectedDate.includes('T')) {
                // Extraer la hora y los minutos (formato HH:mm)
                let timePart = selectedDate.split('T')[1].split('-')[0]; // Extraer "HH:mm:ss"
                let [hours, minutes] = timePart.split(':'); // Separar horas y minutos

                // Asignar la hora y los minutos a starTime
                starTime.value = `${hours}:${minutes}`;
                starTime.disabled = false; // Habilitar el campo de hora de inicio

                // Calcular endTime sumando 1 hora a starTime
                let endTimeDate = new Date(`2000-01-01T${hours}:${minutes}:00`); // Crear objeto Date
                endTimeDate.setHours(endTimeDate.getHours() + 1); // Sumar 1 hora
                let endTimeValue = endTimeDate.toTimeString().slice(0, 5); // Formatear a HH:MM

                // Actualizar el campo endTime
                endTime.value = endTimeValue;
                endTime.disabled = false; // Habilitar el campo de hora de finalización

                // Desmarcar y deshabilitar el checkbox "Todo el día"
                allDayCheckbox.checked = false;
                allDayCheckbox.disabled = true;
            }

            // Mostrar el modal
            $("#modal-edit-create").removeClass("hidden").addClass("show");
        },
        eventDrop: function (info) {
            // Lógica para manejar el evento después de arrastrarlo
            updateEvent(info.event, info.revert); // Pasar info.revert como parámetro
        },
        eventResize: function (info) {
            // Lógica para manejar el evento después de redimensionarlo
            updateEvent(info.event, info.revert); // Pasar info.revert como parámetro
        },
        // Personalización del contenido del evento
        eventContent: function(arg) {
            // Obtener los valores necesarios
            var icon = arg.event.extendedProps.icon || '';
            var title = arg.event.title;
            var priority = arg.event.extendedProps.priority || '';
            
            // Definir los colores según la prioridad
            var priorityColors = {
                'Bajo': '#3b82f6',  // Azul
                'Medio': '#facc15', // Amarillo
                'Alto': '#dc2626'   // Rojo
            };
        
            // Obtener el color correspondiente o dejar vacío si no hay prioridad
            var priorityIcon = priorityColors[priority] 
                ? `<span style="
                        display: inline-block; 
                        width: 12px; 
                        height: 12px; 
                        background-color: ${priorityColors[priority]}; 
                        border-radius: 3px; 
                        margin-right: 5px;
                    "></span>` 
                : '';
        
            // Convertir la hora al formato de 12 horas con AM/PM
            var startDate = new Date(arg.event.start);
            var hours = startDate.getHours();
            var minutes = startDate.getMinutes();
            var formattedTime = 'Todo el día';
        
            // Solo mostrar la hora si no es "00:00"
            if (!(hours === 0 && minutes === 0)) {
                var ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12 || 12; // Convierte 0 en 12
                minutes = minutes < 10 ? '0' + minutes : minutes; // Asegura dos dígitos en minutos
                formattedTime = ` - ${hours}:${minutes} ${ampm}`; // Hora final en formato 12h
            }
        
            // Devolver el contenido del evento
            return {
                html: `
                    <div style="
                        text-align: left; 
                        overflow: hidden; 
                        text-overflow: ellipsis; 
                        white-space: nowrap;
                        max-width: 100%;
                    ">
                        <div>
                            <span style="margin-right: 0.25rem;">${icon}</span>
                            <strong>${title}</strong>
                        </div>
                        <div style="font-size: smaller">
                            ${formattedTime} ${priorityIcon}
                        </div>
                    </div>
                `
            };
        },        
        eventClick: function (info) {
            // Función para formatear la fecha con hora
            function formatFecha(fecha) {
                const opciones = {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric',
                    hour12: false,
                };
                return new Intl.DateTimeFormat('es-MX', opciones).format(fecha);
            }

            function formatDeadline(fecha) {
                if (!fecha) return '';
                return new Date(fecha).toLocaleDateString('es-MX', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }

            function formatTime(date) {
                let hours = date.getHours().toString().padStart(2, "0");
                let minutes = date.getMinutes().toString().padStart(2, "0");
                return `${hours}:${minutes}`;
            }
        
            // Obtén los datos del evento
            var nota = {
                id: info.event.id,
                project_name: info.event.extendedProps.project_name,
                project_id: info.event.extendedProps.project_id,
                user: info.event.extendedProps.user_name,
                icon: info.event.extendedProps.icon,
                color: info.event.backgroundColor,
                title: info.event.title,
                priority: info.event.extendedProps.priority,
                start: info.event.start,
                end: info.event.end,
                deadline: info.event.extendedProps.deadline,
                allDay: info.event.allDay,
                status: info.event.extendedProps.status,
                repeat: info.event.extendedProps.repeat,
                created_at: formatFecha(new Date(info.event.extendedProps.created_at)),
                updated_at: formatFecha(new Date(info.event.extendedProps.updated_at)),
                delegates_name: info.event.extendedProps.delegates_name || 'Sin delegados',
                delegates_id: info.event.extendedProps.delegates_id || [], // Asegúrate de que sea un arreglo
            };

            // Titulo modal
            let colorNote = document.getElementById('color-note');
            let titleNote = document.getElementById('title-note');
            // DATOS A MOSTRAR
            var dateStart = document.getElementById('date-start');
            var dateEnd = document.getElementById('date-end');
            var repeatNote = document.getElementById('repeat-note');
            var userNote = document.getElementById('user-note');
            var priorityNote = document.getElementById('priority-note');
            var delegateNote = document.getElementById('delegate-note');
            var projectNote = document.getElementById('project-note');

            if (nota.allDay === true && nota.end) {
                // Restar un día a la fecha final (para incluir todo el día)
                nota.end = new Date(nota.end); // Crear una copia de la fecha para evitar efectos secundarios
                nota.end.setDate(nota.end.getDate() - 1);
            }
            
            // Actualiza los elementos del titulo del modal
            colorNote.style.backgroundColor = nota.color;
            
            // Lógica para cambiar el texto y borde del título
            let icon = '';
            if (nota.icon != null) {
                icon = nota.icon;
            }
            titleNote.textContent = icon + ' ' + nota.title;
            
            // Cambia el color del texto según el color de fondo
            if (nota.color === '#facc15') {
                titleNote.style.color = 'black';
                titleNote.style.borderColor = 'black'; // Cambia el color del borde
            } else {
                titleNote.style.color = 'white';
                titleNote.style.borderColor = 'white'; // Cambia el color del borde
            }
            
            // Si no es todo el día, muestra el rango completo con hora
            dateStart.textContent = `Inicio: ${formatFecha(nota.start)}`;
            dateEnd.textContent = `Fin: ${formatFecha(nota.end)}`;
            
            // FECHA DE REPETICION
            var noteDeadline = '';
            if (nota.deadline) {
                noteDeadline = ` hasta ${formatDeadline(nota.deadline)}`;
            }
            // Texto de repeticion
            var noteRepeat = '';
            if (nota.repeat == 'Dairy') {
                noteRepeat = 'Todos los días'
            } else if (nota.repeat == 'Weeks') {
                noteRepeat = 'Todas las semanas'
            } else if (nota.repeat == 'Months') {
                noteRepeat = 'Todos los meses'
            } else if (nota.repeat == 'Years') {
                noteRepeat = 'Todos los años'
            } else {
                noteRepeat = 'No se repite'
            }

            repeatNote.textContent = noteRepeat + noteDeadline;
            userNote.textContent = nota.user;
            priorityNote.textContent = nota.priority === null ? 'Sin prioridad' : nota.priority ;
            delegateNote.textContent = nota.delegates_name;
            projectNote.textContent = nota.project_name;
            // --------------------------------------------------------------------------------------------------------------------------------------------
            let modalShow = document.getElementById('modal-show');
            // Manejar la selección de inputs con valores por default
            let idEdit = document.getElementById("id-edit");
            let repeatEditInput = document.getElementById("repeat-edit-input");
            let dateFirstEditInput = document.getElementById("dateFirst-edit-input");
            let dateSecondEditInput = document.getElementById("dateSecond-edit-input");
            let deadlineEditInput = document.getElementById("deadline-edit-input");
            // Manejar la selección de colores
            let circleColorEdit = document.querySelectorAll('.circle-color-edit');
            // Manejar la selección de iconos
            let checkboxesIconEdit = document.querySelectorAll('.icon-style-edit');

            let titleEdit = document.getElementById('title-edit');
            // Manejar la selección de Fecha y Hora
            let allDayEdit = document.getElementById('allDay-edit');
            let dateFirstEdit = document.getElementById('dateFirst-edit');
            let dateSecondEdit = document.getElementById('dateSecond-edit');
            let starTimeEdit = document.getElementById('starTime-edit');
            let endTimeEdit = document.getElementById('endTime-edit');
            // Manejar la selección de Repetir y fecha límite
            let repeatEdit = document.getElementById('repeat-edit');
            let divDeadlineEdit = document.getElementById('divDeadline-edit');
            let deadlineEdit = document.getElementById('deadline-edit');
            let deadlineEditDisabled = document.getElementById('deadline-edit-disabled');
            // Manejar la selección de prioridad
            let priority1Edit = document.getElementById('priority1-edit');
            let priority2Edit = document.getElementById('priority2-edit');
            let priority3Edit = document.getElementById('priority3-edit');

            let project_idEdit = document.getElementById('project_id-edit');
            // Manejar la selección de delegados
            let delegateCheckboxes = document.querySelectorAll('.delegate-edit');

            // Actualiza los colores (checkboxes)
            circleColorEdit.forEach((checkbox) => {
                // Comprueba si el checkbox coincide con el color seleccionado
                if (checkbox.value === nota.color) {
                    checkbox.checked = true;
                    // Agrega la clase 'selected' al span relacionado
                    checkbox.nextElementSibling.classList.add('selected');
                } else {
                    checkbox.checked = false;
                    // Remueve la clase 'selected' del span relacionado
                    checkbox.nextElementSibling.classList.remove('selected');
                }
            });

            // Actualiza los colores (checkboxes)
            checkboxesIconEdit.forEach((checkbox) => {
                // Comprueba si el checkbox coincide con el color seleccionado
                if (checkbox.value === nota.icon) {
                    checkbox.checked = true;
                    // Agrega la clase 'selected' al span relacionado
                    checkbox.nextElementSibling.classList.add('selected');
                } else {
                    checkbox.checked = false;
                    // Remueve la clase 'selected' del span relacionado
                    checkbox.nextElementSibling.classList.remove('selected');
                }
            });

            // Actualiza los inputs del formulario con la información de la nota
            idEdit.value = nota.id;
            repeatEditInput.value = nota.repeat || "Once";
            dateFirstEditInput.value = nota.start.toISOString().split("T")[0];
            dateSecondEditInput.value = nota.end.toISOString().split("T")[0];

            titleEdit.value = nota.title;
            allDayEdit.checked = nota.allDay;
            dateFirstEdit.value = nota.start.toISOString().split("T")[0];
            dateSecondEdit.value = nota.end.toISOString().split("T")[0];

            if (nota.allDay) {
                starTimeEdit.disabled = true;
                starTimeEdit.value = '';
                endTimeEdit.disabled = true;
                endTimeEdit.value = '';
            } else {
                starTimeEdit.disabled = false;
                starTimeEdit.value = formatTime(nota.start); // Usa la hora local
                endTimeEdit.disabled = false;
                endTimeEdit.value = formatTime(nota.end); // Usa la hora local
            }

            repeatEdit.value = nota.repeat || "Once";
            
            if (nota.repeat != 'Once') {
                divDeadlineEdit.classList.remove('hidden');
                // Verificar si nota.deadline no es null
                if (nota.deadline) {
                    let deadlineDate = new Date(nota.deadline); // Convertir a objeto Date
                    deadlineEditDisabled.value = deadlineDate.toISOString().split("T")[0]; // Formato YYYY-MM-DD
                    deadlineEdit.value = deadlineDate.toISOString().split("T")[0]; // Formato YYYY-MM-DD
                    deadlineEditInput.value = deadlineDate.toISOString().split('T')[0];
                } else {
                    deadlineEditDisabled.value = '';
                    deadlineEdit.value = ''; // Vaciar si no hay fecha
                    deadlineEditInput.value = '';
                }
            } 

            // Actualiza la prioridad
            priority1Edit.checked = nota.priority === "Alto";
            priority2Edit.checked = nota.priority === "Medio";
            priority3Edit.checked = nota.priority === "Bajo";

            project_idEdit.value = String(nota.project_id) || String(0);
            
            // Actualiza los delegados (checkboxes)
            delegateCheckboxes.forEach((checkbox) => {
                checkbox.checked = nota.delegates_id.includes(parseInt(checkbox.value)); // Comprueba por ID
            });

            // Obtén el ID del evento
            var eventId = info.event.id;
            // Emitir evento a Livewire para actualizar el ID
            Livewire.emit('setEventId', eventId);
        
            // Muestra el modal
            modalShow.classList.remove("hidden");
            modalShow.classList.add("show");
        }        
    });

    calendar.render();

    function updateEvent(event, revertFunction) {
        // Determinar si el evento es parte de una serie de eventos repetidos
        const isRepeatedEvent = event.extendedProps.repeat !== 'Once';
    
        // Mensaje predeterminado
        let swalMessage = '¿Deseas modificar la fecha de este evento?';
    
        // Si el evento es parte de una serie de eventos repetidos, agregar un mensaje adicional
        if (isRepeatedEvent) {
            swalMessage += '<br><br><strong style="color: red;">Advertencia:</strong> Si eliges "Modificar todos los eventos", se eliminará toda la información de los chats asociados a estos eventos.';
        }
    
        // Mostrar un SweetAlert2 para confirmar la modificación
        Swal.fire({
            title: '¿Estás seguro?',
            html: swalMessage, // Usar HTML para mostrar el mensaje
            icon: 'question',
            showCancelButton: true,
            showDenyButton: isRepeatedEvent, // Mostrar el botón "Modificar todos" solo si es un evento repetido
            confirmButtonColor: '#202a33', // Color del botón de confirmación
            denyButtonColor: '#202a33', // Color del botón "Modificar todos"
            cancelButtonColor: '#d33', // Color del botón de cancelar
            confirmButtonText: isRepeatedEvent ? 'Modificar solo este evento' : 'Sí, modificar',
            denyButtonText: 'Modificar todos los eventos',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed || result.isDenied) {

                // Validar si la fecha 'end' es null
                if (!event.end) {
                    toastr.warning('No es posible realizar el movimiento solicitado. Por favor, edite el evento manualmente haciendo clic sobre él');
                    revertFunction(); // Revertir el cambio en el calendario
                    return; // Detener la ejecución de la función
                }
                
                const eventData = {
                    id: event.id,
                    start: new Date(event.start.getTime() - (event.start.getTimezoneOffset() * 60000)).toISOString(),
                    end: event.end ? new Date(event.end.getTime() - (event.end.getTimezoneOffset() * 60000)).toISOString() : null,
                    repeat: event.extendedProps.repeat,
                    updateAllEvents: result.isDenied, // Enviar true si el usuario eligió "Modificar todos los eventos"
                    updateDates: true, // Modificacion de fecha y hora al arrastre
                    allDay: event.allDay, // Indicar si el evento es de todo el día
                };
                // Si el evento es de todo el día (allDay), restar un día a la fecha "end"
                if (eventData.allDay && eventData.end) {
                    const endDate = new Date(eventData.end);
                    endDate.setDate(endDate.getDate() - 1); // Restar un día
                    eventData.end = endDate.toISOString(); // Actualizar la fecha "end"
                }
                
                // Si el evento es de todo el día, ajustar la fecha "end"
                if (event.allDay) {
                    const endDate = new Date(event.end);
                    endDate.setHours(0, 0, 0, 0); // Establecer la hora a 00:00:00
                    eventData.end = endDate.toISOString();
                }
    
                // Mostrar la animación de carga
                document.querySelector('.loadingspinner').parentElement.classList.remove('hidden');
    
                // Envía la solicitud AJAX para actualizar el evento
                fetch(`/calendar/${event.id}`, {
                    method: 'PUT', // Usa PUT para actualizar
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Asegúrate de incluir el token CSRF
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(eventData), // Envía los datos actualizados
                })
                .then(response => {
                    if (!response.ok) {
                        // Si la respuesta no es exitosa, captura los errores de validación
                        return response.json().then(err => {
                            throw err; // Lanza el error para que sea capturado en el catch
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Muestra un toastr de éxito
                        toastr.success(data.message || 'Evento actualizado correctamente');
                    } else {
                        // Muestra un toastr de error
                        toastr.error(data.message || 'Error al actualizar el evento');
                    }
                })
                .catch(error => {
                    // Muestra un toastr de error con el mensaje del servidor
                    toastr.error(error.message || 'Hubo un error en la solicitud');
                    console.error('Error:', error);
    
                    // Revertir el cambio en el calendario
                    revertFunction(); // Usar la función revert pasada como parámetro
                })
                .finally(() => {
                    // Ocultar la animación de carga (se ejecuta siempre, tanto en éxito como en error)
                    document.querySelector('.loadingspinner').parentElement.classList.add('hidden');
                });
            } else {
                // El usuario canceló la modificación
                revertFunction(); // Usar la función revert pasada como parámetro
            }
        });
    }

    // Selecciona el botón de guardar
    document.getElementById('btn-update-notion').addEventListener('click', function () {
        // Obtener los valores de las fechas
        var dateFirstEdit = document.getElementById('dateFirst-edit').value;
        var dateSecondEdit = document.getElementById('dateSecond-edit').value;
        // Obtener los valores de las fechas
        var starTimeEdit = document.getElementById('starTime-edit').value;
        var endTimeEdit = document.getElementById('endTime-edit').value;

        // Convertir las fechas a objetos Date
        var dateFirstEditObj = new Date(dateFirstEdit);
        var dateSecondEditObj = new Date(dateSecondEdit);

        // Validar si dateSecond es menor que dateFirst
        if (dateSecondEditObj < dateFirstEditObj) {
            toastr.error('La fecha final no puede ser menor que la fecha inicial.');
            return; // Detener la ejecución de la función
        } 

        // Validar si dateSecond es menor que dateFirst
        if (starTimeEdit > endTimeEdit) {
            toastr.error('La hora de inicio no puede ser mayor o igual que la hora de finalización.');
            return; // Detener la ejecución de la función
        } 
        // Obtén los datos del formulario
        const form = document.getElementById('edit-notion-form');
        const formData = new FormData(form);
        const noteId = document.getElementById('id-edit').value; // Obtén el ID de la nota
        const repeatValue = document.getElementById('repeat-edit-input').value; // Obtén el valor de repeat-edit-input
        const deadlineValue = document.getElementById('deadline-edit-input').value; // Obtén el valor de repeat-edit-input

        // Convertir FormData a un objeto JSON
        const data = {};
        formData.forEach((value, key) => {
            if (key.endsWith('[]')) {
                // Manejar arreglos (por ejemplo, delegate_id[])
                const arrayKey = key.replace('[]', '');
                if (!data[arrayKey]) {
                    data[arrayKey] = [];
                }
                data[arrayKey].push(value);
            } else {
                data[key] = value;
            }
        });
        
        // Función para enviar la solicitud al backend
        const sendRequest = (editAllEvents, changeRepetition = false, noRepeat = false, deadlineChange = false) => {
            // Agrega la variable editAllEvents al objeto data
            data.editAllEvents = editAllEvents;
            data.changeRepetition = changeRepetition;
            data.noRepeat = noRepeat;
            data.deadlineChange = deadlineChange;
            
            // Mostrar la animación de carga
            document.querySelector('.loadingspinner').parentElement.classList.remove('hidden');
            console.log(data);
            
            // Envía la solicitud AJAX
            fetch(`/calendar/${noteId}`, {
                method: 'PUT', // Usa PUT para actualizar
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Asegúrate de incluir el token CSRF
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data) // Envía los datos como JSON
            })
            .then(response => {
                if (!response.ok) {
                    // Si la respuesta no es exitosa, captura los errores de validación
                    return response.json().then(err => {
                        throw err; // Lanza el error para que sea capturado en el catch
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Muestra un toastr de éxito
                    toastr.success(data.message || 'Nota actualizada');
                    // Recarga la página o actualiza el calendario
                    window.location.reload();
                } else {
                    // Muestra un toastr de error
                    toastr.error(data.message || 'Error al actualizar');
                }
            })
            .catch(error => {
                // Muestra un toastr de error con el mensaje del servidor
                toastr.error(error.message || 'Hubo un error en la solicitud');
                console.error('Error:', error);
            })
            .finally(() => {
                // Ocultar la animación de carga (se ejecuta siempre, tanto en éxito como en error)
                document.querySelector('.loadingspinner').parentElement.classList.add('hidden');
            });
        }
        // Verifica si el valor de repeat-edit-input es diferente de "Once"
        if (repeatValue !== 'Once') {
            // Evento original en repetición
            if (repeatValue === data.repeat) {
                // Comparar fechas para detectar cambios
                if (deadlineValue == data.deadline) {
                    // Preguntar si se actualiza este evento o todos
                    Swal.fire({
                        title: '¿Qué deseas editar?',
                        html: `
                            <strong style="color: red;">Advertencia:</strong> Si eliges "Todos los eventos", se eliminará toda la información de los chats asociados a estos eventos.
                        `, // Usar HTML para mostrar el mensaje
                        icon: 'question',
                        showCancelButton: true,
                        showCloseButton: true, // Habilita la "X" de cierre
                        confirmButtonColor: '#202a33',
                        confirmButtonText: 'Todos los eventos',
                        cancelButtonText: 'Solo este evento',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // El usuario eligió editar todos los eventos
                            sendRequest(true);
                        } else {
                            // El usuario eligió editar solo este evento o cerró el modal con la "X"
                            if (result.dismiss === Swal.DismissReason.close) {
                                // El usuario cerró el modal con la "X"
                                Swal.close(); // Cierra el modal sin hacer nada
                            } else {
                                // El usuario eligió editar solo este evento
                                sendRequest(false);
                            }
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Cambio en la fecha límite',
                        html: `
                            <p>Se ha detectado un cambio en la fecha límite del evento.</p>
                            <p><strong>Fecha anterior:</strong> ${deadlineValue || 'Sin fecha límite'}</p>
                            <p><strong>Nueva fecha:</strong> ${data.deadline || 'Sin fecha límite'}</p>
                            <p>Esto <strong style="color: red;">sumará o restará eventos</strong> relacionados.</p>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#202a33',
                        confirmButtonText: 'Actualizar todos los eventos',
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Si el usuario confirma, continuar con la actualización de todos los eventos
                            sendRequest(true, false, false, true);
                        } else {
                            // Si cancela o cierra el modal, no hacer nada
                            Swal.close();
                        }
                    });
                }
            } else {
                // Cambio de repetición en request
                Swal.fire({
                    title: 'Cambio de repetición',
                    html: `
                        Al cambiar la repetición, se aplicará a todos los eventos relacionados. 
                        <br><br>
                        <strong style="color: red;">Advertencia:</strong> Esta acción eliminará toda la información de los chats asociados a estos eventos.
                        <br><br>
                        ¿Deseas continuar?
                    `, // Usar HTML para mostrar el mensaje
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#202a33',
                    cancelButtonColor: '#ef4444',
                    confirmButtonText: 'Sí, aplicar a todos',
                    cancelButtonText: 'No, cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // El usuario eligió aplicar el cambio de repetición a todos los eventos
                        sendRequest(true, true);
                    } else {
                        // El usuario eligió cancelar
                        Swal.close();
                    }
                });
            }
        } else {
            // Si el valor es "Once", envía la solicitud directamente
            sendRequest(false, false, true);
        }
    });
    // Selecciona el botón de eliminar
    document.getElementById('btn-delete').addEventListener('click', function () {
        // Obtén el ID del evento a eliminar
        const noteId = document.getElementById('id-edit').value;
        const repeatValue = document.getElementById('repeat-edit-input').value; // Obtén el valor de repeat-edit-input
    
        // Función para eliminar el evento
        const deleteEvent = (noteId, deleteAllEvents = false) => {
            // Mostrar la animación de carga
            document.querySelector('.loadingspinner').parentElement.classList.remove('hidden');
            
            // Envía la solicitud AJAX para eliminar el evento
            fetch(`/calendar/${noteId}`, {
                method: 'DELETE', // Usa DELETE para eliminar
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Asegúrate de incluir el token CSRF
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ deleteAllEvents }), // Envía la variable deleteAllEvents
            })
            .then(response => {
                if (!response.ok) {
                    // Si la respuesta no es exitosa, captura los errores de validación
                    return response.json().then(err => {
                        throw err; // Lanza el error para que sea capturado en el catch
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Muestra un toastr de éxito
                    toastr.success(data.message || 'Evento eliminado correctamente');
                    // Recarga la página o actualiza el calendario
                    window.location.reload();
                } else {
                    // Muestra un toastr de error
                    toastr.error(data.message || 'Error al eliminar el evento');
                }
            })
            .catch(error => {
                // Muestra un toastr de error con el mensaje del servidor
                toastr.error(error.message || 'Hubo un error en la solicitud');
                console.error('Error:', error);
            })
            .finally(() => {
                // Ocultar la animación de carga (se ejecuta siempre, tanto en éxito como en error)
                document.querySelector('.loadingspinner').parentElement.classList.add('hidden');
            });
        };

        // Verifica si el valor de repeat-edit-input es diferente de "Once"
        if (repeatValue !== 'Once') {
            // Muestra un SweetAlert2 para preguntar al usuario
            Swal.fire({
                title: '¿Qué deseas eliminar?',
                text: '¿Deseas eliminar solo este evento o todos los eventos relacionados?',
                icon: 'question',
                showCancelButton: true,
                showCloseButton: true, // Habilita la "X" de cierre
                confirmButtonColor: '#202a33', // Color del botón de confirmación
                confirmButtonText: 'Todos los eventos',
                cancelButtonText: 'Solo este evento',
            }).then((result) => {
                if (result.isConfirmed) {
                    // El usuario eligió eliminar todos los eventos
                    deleteEvent(noteId, true); // deleteAllEvents = true
                } else if (result.dismiss === Swal.DismissReason.close) {
                    // El usuario cerró el modal con la "X"
                    Swal.close(); // Cierra el modal sin hacer nada
                } else {
                    // El usuario eligió eliminar solo este evento
                    deleteEvent(noteId, false); // deleteAllEvents = false
                }
            });
        } else {
            // Muestra un SweetAlert2 para confirmar la eliminación
            Swal.fire({
                title: '¿Seguro que deseas eliminar este elemento?',
                text: 'Esta acción es irreversible',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#202a33', // Color del botón de confirmación
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    // El usuario confirmó la eliminación
                    deleteEvent(noteId, false); // deleteAllEvents = false (solo este evento)
                }
            });
        }
    });

    // Ocultar la animación de carga cuando el calendario se haya cargado
    loadingPage.classList.add('hidden'); // Oculta el contenedor de carga
});
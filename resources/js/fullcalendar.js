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
            // Construye el contenido del evento
            var icon = arg.event.extendedProps.icon;
            var title = arg.event.title;
            // var status = arg.event.extendedProps.status;
            var project_name = arg.event.extendedProps.project_name;
            var priority = arg.event.extendedProps.priority || 'Sin prioridad';
            if (icon == null) {
                icon = '';
            }
            // Devuelve un objeto con nodos HTML
            return {
                html: `
                    <div style="
                        text-align: left; 
                        overflow: hidden; 
                        text-overflow: ellipsis; 
                        white-space: nowrap;
                        max-width: 100%; /* Ajusta esto según tus necesidades */
                    ">
                        <div>
                            <span style="margin-right: 0.25rem;">${icon}</span> <!-- Ícono de corazón con HTML entity -->
                            <strong>${title}</strong>
                        </div>
                        <div style="font-size: smaller">
                        ${priority}, ${project_name}
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
                allDay: info.event.allDay,
                status: info.event.extendedProps.status,
                repeat: info.event.extendedProps.repeat,
                created_at: formatFecha(new Date(info.event.extendedProps.created_at)),
                updated_at: formatFecha(new Date(info.event.extendedProps.updated_at)),
                delegates_name: info.event.extendedProps.delegates_name || 'Sin delegados',
                delegates_id: info.event.extendedProps.delegates_id || [], // Asegúrate de que sea un arreglo
            };
        
            // Actualiza los elementos del modal con la información de la nota
            document.getElementById('color-note').style.backgroundColor = nota.color;
        
            // Lógica para cambiar el texto y borde del título
            var titleNoteElement = document.getElementById('title-note');
            let icon = '';
            if (nota.icon != null) {
                icon = nota.icon;
            }
            titleNoteElement.textContent = icon + ' ' + nota.title;
        
            // Cambia el color del texto según el color de fondo
            if (nota.color === '#facc15') {
                titleNoteElement.style.color = 'black';
                titleNoteElement.style.borderColor = 'black'; // Cambia el color del borde
            } else {
                titleNoteElement.style.color = 'white';
                titleNoteElement.style.borderColor = 'white'; // Cambia el color del borde
            }
        
            // Mostrar fecha dependiendo de si es allDay o no
            var dateStartElement = document.getElementById('date-start');
            var dateEndElement = document.getElementById('date-end');

            // Si no es todo el día, muestra el rango completo con hora
            dateStartElement.textContent = `Inicio: ${formatFecha(nota.start)}`;
            dateEndElement.textContent = `Fin: ${formatFecha(nota.end)}`;

        
            // Actualiza el resto de la información en el modal
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
            document.getElementById('repeat-note').textContent = noteRepeat;
            document.getElementById('user-note').textContent = nota.user;
            document.getElementById('priority-note').textContent = nota.priority === null ? 'Sin prioridad' : nota.priority ;
            document.getElementById('delegate-note').textContent = nota.delegates_name;
            document.getElementById('project-note').textContent = nota.project_name;
            // --------------------------------------------------------------------------------------------------------------------------------------------
            // Actualiza los colores (checkboxes)
            const colorCheckboxes = document.querySelectorAll('.circle-color-edit');
            colorCheckboxes.forEach((checkbox) => {
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
            const iconsCheckboxes = document.querySelectorAll('.icon-style-edit');
            iconsCheckboxes.forEach((checkbox) => {
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
            document.getElementById("id-edit").value = nota.id;
            document.getElementById("title-edit").value = nota.title;

            document.getElementById("allDay-edit").checked = nota.allDay;
            document.getElementById("dateFirst-edit").value = nota.start.toISOString().split("T")[0];
            document.getElementById("dateSecond-edit").value = nota.end.toISOString().split("T")[0];

            const startTimeInput = document.getElementById("starTime-edit");
            const endTimeInput = document.getElementById("endTime-edit");

            function formatTime(date) {
                let hours = date.getHours().toString().padStart(2, "0");
                let minutes = date.getMinutes().toString().padStart(2, "0");
                return `${hours}:${minutes}`;
            }

            if (nota.allDay) {
                startTimeInput.disabled = true;
                startTimeInput.value = '';
                endTimeInput.disabled = true;
                endTimeInput.value = '';
            } else {
                startTimeInput.disabled = false;
                startTimeInput.value = formatTime(nota.start); // Usa la hora local
                endTimeInput.disabled = false;
                endTimeInput.value = formatTime(nota.end); // Usa la hora local
            }

            document.getElementById("repeat-edit").value = nota.repeat || "Once";
            document.getElementById("repeat-edit-input").value = nota.repeat || "Once";
            document.getElementById("project_id-edit").value = String(nota.project_id) || String(0);

            // Actualiza la prioridad
            document.getElementById("priority1-edit").checked = nota.priority === "Alto";
            document.getElementById("priority2-edit").checked = nota.priority === "Medio";
            document.getElementById("priority3-edit").checked = nota.priority === "Bajo";

            // Actualiza los delegados (checkboxes)
            const delegateCheckboxes = document.querySelectorAll('.delegate-edit');
            delegateCheckboxes.forEach((checkbox) => {
                checkbox.checked = nota.delegates_id.includes(parseInt(checkbox.value)); // Comprueba por ID
            });

            // Obtén el ID del evento
            var eventId = info.event.id;
            // Emitir evento a Livewire para actualizar el ID
            Livewire.emit('setEventId', eventId);
        
            // Muestra el modal
            $("#modal-show").removeClass("hidden").addClass("show");
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
        // Obtén los datos del formulario
        const form = document.getElementById('edit-notion-form');
        const formData = new FormData(form);
        const noteId = document.getElementById('id-edit').value; // Obtén el ID de la nota
        const repeatValue = document.getElementById('repeat-edit-input').value; // Obtén el valor de repeat-edit-input

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
        const sendRequest = (editAllEvents, changeRepetition = false, noRepeat = false) => {
            // Agrega la variable editAllEvents al objeto data
            data.editAllEvents = editAllEvents;
            data.changeRepetition = changeRepetition;
            data.noRepeat = noRepeat;
            
            // Mostrar la animación de carga
            document.querySelector('.loadingspinner').parentElement.classList.remove('hidden');

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
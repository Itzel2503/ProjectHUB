import $ from 'jquery';
import DataTable from 'datatables.net-dt';

window.$ = window.jQuery = $;

document.addEventListener("DOMContentLoaded", () => {
    const table = $('#mytable').DataTable({
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"  // Cargar idioma en español
        },
        searching: true,  // Desactiva la barra de búsqueda predeterminada
        lengthChange: false,  // Desactiva el "Mostrar registros" predeterminado
        pageLength: 50,  // Establece el número de registros por página en 50
        paging: true,
        info: false,
        dom: 'rt', // Solo tabla (sin controles por defecto)
        headerCallback: function (thead, data, start, end, display) {
            // Agregar clases personalizadas para encabezados
            $(thead).find('th').addClass('headTable border-0 text-left px-2 py-3');
        },
        createdRow: function (row, data, dataIndex) {
            // Agregar clases personalizadas para filas
            $(row).addClass('trTable');
        },
        footerCallback: function (tfoot, data, start, end, display) {
            // Agregar clases personalizadas para pie de tabla
            $(tfoot).find('th').addClass('headTable border-0 text-left px-2 py-3');
        },
        initComplete: function() {
            // Vincula el input de búsqueda personalizado con el filtro de DataTables
            $('#tableSearch').on('keyup', function() {
                $('#mytable').DataTable().search(this.value).draw();
            });
            // Vincula el select de mostrar registros con la funcionalidad de DataTables
            $('#tableLength').on('change', function() {
                var length = $(this).val();
                $('#mytable').DataTable().page.len(length).draw();
            });
            // Filtro por delegados
            $('#delegateFilter').on('change', function() {
                const delegate = $(this).val();
                
                if (delegate) {
                    // Escapamos caracteres especiales para evitar errores en la expresión regular
                    const escapedDelegate = delegate.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                    table.columns(1).search(escapedDelegate).draw();
                } else {
                    table.columns(1).search('').draw();
                }
            });
            // Filtro por estado con checkboxes
            // Mostrar/ocultar opciones del filtro de estados
            const stateFilter = $('#stateFilter');
            const selectLabel = stateFilter.find('.select-label');
            const selectOptions = stateFilter.find('.select-options');

            selectLabel.on('click', () => {
                selectOptions.toggleClass('hidden');
            });

            stateFilter.find('input[type="checkbox"]').on('change', function () {
                const selectedStates = [];
                stateFilter.find('input[type="checkbox"]:checked').each(function () {
                    selectedStates.push($(this).val());
                });

                if (selectedStates.length > 0) {
                    // Construir expresión regular para múltiples valores
                    const regex = selectedStates.join('|');
                    table.columns(2).search(regex, true, false).draw();
                } else {
                    // Si no hay seleccionados, limpiar el filtro
                    table.columns(2).search('').draw();
                }
            });
        },
        drawCallback: updatePagination,
    });

    const paginationControls = {
        prev: $('#prevPage'),
        next: $('#nextPage'),
        info: $('#pageInfo')
    };

    function updatePagination() {
        const pageInfo = table.page.info();

        // Actualiza la información de página
        paginationControls.info.text(`Página ${pageInfo.page + 1} de ${pageInfo.pages}`);

        // Gestiona botones
        if (pageInfo.page === 0) {
            paginationControls.prev.addClass('btnDisabled');
        } else {
            paginationControls.prev.removeClass('btnDisabled');
            paginationControls.prev.addClass('btnSave');
        }

        if (pageInfo.page === pageInfo.pages - 1 || pageInfo.pages === 0) {
            paginationControls.next.addClass('btnDisabled');
        } else {
            paginationControls.next.removeClass('btnDisabled');
        }
    }

    // Controladores de eventos
    paginationControls.prev.on('click', () => {
        if (!paginationControls.prev.hasClass('btnDisabled')) {
            table.page('previous').draw('page');
        }
    });

    paginationControls.next.on('click', () => {
        if (!paginationControls.next.hasClass('btnDisabled')) {
            table.page('next').draw('page');
        }
    });
});
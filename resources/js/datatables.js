import $ from 'jquery';
import DataTable from 'datatables.net-dt';

window.$ = window.jQuery = $;

document.addEventListener("DOMContentLoaded", () => {
    const table = $('#mytable').DataTable({
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"  // Cargar idioma en español
        },
        searching: false,  // Desactiva la barra de búsqueda predeterminada
        lengthChange: false,  // Desactiva el "Mostrar registros" predeterminado
        paging: true,
        info: false,
        dom: 'rt', // Solo tabla (sin controles por defecto)
        createdRow: function (row, data, dataIndex) {
            // Agregar clases personalizadas para filas
            $(row).addClass('trTable');
        },
        headerCallback: function (thead, data, start, end, display) {
            // Agregar clases personalizadas para encabezados
            $(thead).find('th').addClass('headTable border-0 text-left px-2 py-3');
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
        console.log(pageInfo.page);
        
        if (pageInfo.page === 0) {
            paginationControls.prev.addClass('btnDisabled');
        } else {
            paginationControls.prev.removeClass('btnDisabled');
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
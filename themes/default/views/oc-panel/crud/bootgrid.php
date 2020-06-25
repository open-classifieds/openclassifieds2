var grid = $("#grid-data-api").bootgrid({
    ajax: true,
    url: $('#filter_buttons').data('url'),
    rowCount: [10,25,50,100],
    <?if(core::count($search_fields) > 0):?>
        searchSettings: {
            delay: 500,
            characters: 3
        },
    <?endif?>
    formatters: {
        "commands": function(column, row)
        {
            edit_button = "<?if ($controller->allowed_crud_action('update')):?><a href=\"<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'update'))?>/"+ row.<?=$element->primary_key()?> +"\" class=\"font-medium text-blue-600 hover:text-blue-900 command-edit\" data-row-id=\"" + row.<?=$element->primary_key()?> + "\"><?= __('Edit') ?></a><?endif?>";
            dele_button = "<?if ($controller->allowed_crud_action('delete')):?><a href=\"<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'delete'))?>/"+ row.<?=$element->primary_key()?> +"\" class=\"font-medium ml-6 text-red-600 hover:text-red-900 command-edit command-delete\" data-row-id=\"" + row.<?=$element->primary_key()?> + "\" title=\"<?=__('Are you sure you want to delete?')?>\" data-btnOkLabel=\"<?=__('Yes, definitely!')?>\" data-btnCancelLabel=\"<?=__('No way!')?>\"><?= __('Delete') ?></a><?endif?>";
            extra_button = "<?if (core::count($buttons) > 0):?><?
            foreach($buttons as $button):?><a href=\"<?=$button['url']?>"+ row.<?=$element->primary_key()?> +"\" class=\"<?=$button['class']?> font-medium ml-6 text-indigo-600 hover:text-indigo-900\" data-row-id=\"" + row.<?=$element->primary_key()?> + "\" title=\"<?=$button['title']?>\" <?=isset($button['attrs']) ? addslashes(HTML::attributes($button['attrs'])) : NULL?>><i class=\"<?=$button['icon']?>\"></i> <?=$button['title']?></a><?endforeach?><?endif?>";
            return edit_button+dele_button+extra_button;
        }
    },
    css: {
        actions: "actions relative z-0 inline-flex shadow-sm rounded-md", // must be a unique class name or constellation of class names within the header and footer
        center: "text-center",
        columnHeaderAnchor: "column-header-anchor", // must be a unique class name or constellation of class names within the column header cell
        columnHeaderText: "text",
        dropDownItem: "dropdown-item", // must be a unique class name or constellation of class names within the actionDropDown,
        dropDownItemButton: "dropdown-item-button", // must be a unique class name or constellation of class names within the actionDropDown
        dropDownItemCheckbox: "dropdown-item-checkbox", // must be a unique class name or constellation of class names within the actionDropDown
        dropDownMenu: "dropdown btn-group", // must be a unique class name or constellation of class names within the actionDropDown
        dropDownMenuItems: "dropdown-menu pull-right", // must be a unique class name or constellation of class names within the actionDropDown
        dropDownMenuText: "dropdown-text", // must be a unique class name or constellation of class names within the actionDropDown
        footer: "tw-bootgrid-footer",
        header: "tw-bootgrid-header",
        icon: "inline-block h-4 w-4 text-gray-500",
        iconColumns: "<svg fill=\"none\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path d=\"M4 6h16M4 10h16M4 14h16M4 18h16\"></path></svg>",
        iconDown: "glyphicon-chevron-down",
        iconRefresh: "<svg fill=\"none\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path d=\"M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15\"></path></svg>",
        iconSearch: "glyphicon-search",
        iconUp: "glyphicon-chevron-up",
        infos: "infos", // must be a unique class name or constellation of class names within the header and footer,
        left: "text-left",
        pagination: "pagination", // must be a unique class name or constellation of class names within the header and footer
        paginationButton: "button", // must be a unique class name or constellation of class names within the pagination

        /**
         * CSS class to select the parent div which activates responsive mode.
         *
         * @property responsiveTable
         * @type String
         * @default "table-responsive"
         * @for css
         * @since 1.1.0
         **/
        responsiveTable: "table-responsive",

        right: "text-right",
        search: "search form-group", // must be a unique class name or constellation of class names within the header and footer
        searchField: "search-field form-control",
        selectBox: "select-box", // must be a unique class name or constellation of class names within the entire table
        selectCell: "select-cell", // must be a unique class name or constellation of class names within the entire table

        /**
         * CSS class to highlight selected rows.
         *
         * @property selected
         * @type String
         * @default "active"
         * @for css
         * @since 1.1.0
         **/
        selected: "active",

        sortable: "sortable",
        table: "bootgrid-table table"
    },
    templates: {
        actionButton: "<button class=\"relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150\" type=\"button\" title=\"{{ctx.text}}\">{{ctx.content}}</button>",
        actionDropDown: "<span class=\"{{css.dropDownMenu}}\" x-data=\"{ open: false }\" class=\"-ml-px relative block\"><button @click=\"open = !open\" @click.away=\"open = false\" type=\"button\" class=\"relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-500 hover:text-gray-400 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150\"><span class=\"{{css.dropDownMenuText}}\">{{ctx.content}}</span><svg class=\"h-5 w-5\" fill=\"currentColor\" viewBox=\"0 0 20 20\"><path fill-rule=\"evenodd\" d=\"M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z\" clip-rule=\"evenodd\"/></svg></button><div x-show=\"open\" style=\"display: none;\" x-transition:enter=\"transition ease-out duration-100\" x-transition:enter-start=\"transform opacity-0 scale-95\" x-transition:enter-end=\"transform opacity-100 scale-100\" x-transition:leave=\"transition ease-in duration-75\" x-transition:leave-start=\"transform opacity-100 scale-100\" x-transition:leave-end=\"transform opacity-0 scale-95\" class=\"origin-top-right absolute right-0 mt-2 -mr-1 w-56 rounded-md shadow-lg\"><div class=\"rounded-md bg-white shadow-xs\"><div class=\"{{css.dropDownMenuItems}} py-1\"></div></div></div></span>",
        actionDropDownItem: "<span><a data-action=\"{{ctx.action}}\" class=\"{{css.dropDownItem}} {{css.dropDownItemButton}} block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900\">{{ctx.text}}</a></span>",
        actionDropDownCheckboxItem: "<li><label class=\"{{css.dropDownItem}}\"><input name=\"{{ctx.name}}\" type=\"checkbox\" value=\"1\" class=\"{{css.dropDownItemCheckbox}}\" {{ctx.checked}} /> {{ctx.label}}</label></li>",
        actions: "<div class=\"{{css.actions}}\"></div>",
        body: "<tbody class=\"bg-white\"></tbody>",
        cell: "<td class=\"{{ctx.css}} px-6 py-4 whitespace-no-wrap border-b border-gray-200\" style=\"{{ctx.style}}\"><div class=\"text-sm leading-5 text-gray-900\">{{ctx.content}}</div></td>",
        footer: "<div id=\"{{ctx.id}}\" class=\"{{css.footer}} bg-white px-4 py-3 flex items-center justify-between sm:px-6\"><div class=\"flex-1 flex justify-between items-center\"><div><span class=\"{{css.pagination}} relative z-0 inline-flex shadow-sm\"></span></div><div class=\"infoBar text-sm leading-5 text-gray-700\"><p class=\"{{css.infos}}\"></p></div></div></div>",
        header: "<div id=\"{{ctx.id}}\" class=\"{{css.header}} bg-white px-4 py-5 border-b border-gray-200 sm:px-6\"><div class=\"-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-no-wrap\"><div class=\"ml-4 mt-2\"><div class=\"{{css.search}}\"></div></div><div class=\"ml-4 mt-2 flex-shrink-0\"><div class=\"{{css.actions}}\"></div></div></div></div>",
        headerCell: "<th data-column-id=\"{{ctx.column.id}}\" class=\"{{ctx.css}} px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider\" style=\"{{ctx.style}}\"><a href=\"javascript:void(0);\" class=\"{{css.columnHeaderAnchor}} {{ctx.sortable}}\"><span class=\"{{css.columnHeaderText}}\">{{ctx.column.text}}</span>{{ctx.icon}}</a></th>",
        icon: "<span class=\"{{css.icon}}\">{{ctx.iconCss}}</span>",
        infos: "<div class=\"{{css.infos}}\">{{lbl.infos}}</div>",
        loading: "<tr><td colspan=\"{{ctx.columns}}\" class=\"loading\">{{lbl.loading}}</td></tr>",
        noResults: "<tr><td colspan=\"{{ctx.columns}}\" class=\"no-results\">{{lbl.noResults}}</td></tr>",
        pagination: "<div class=\"{{css.pagination}} border border-gray-300 rounded-md overflow-hidden\"></div>",
        paginationItem: "<span class=\"{{ctx.css}}\"><a data-page=\"{{ctx.page}}\" class=\"{{css.paginationButton}} -ml-px relative inline-flex items-center px-4 py-2 border-l border-r border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150\">{{ctx.text}}</a></span>",
        rawHeaderCell: "<th class=\"{{ctx.css}}\">{{ctx.content}}</th>", // Used for the multi select box
        row: "<tr{{ctx.attr}}>{{ctx.cells}}</tr>",
        search: "<div class=\"{{css.search}}\"><div class=\"input-group\"><span class=\"{{css.icon}} input-group-addon {{css.iconSearch}}\"></span> <input type=\"text\" class=\"{{css.searchField}}\" placeholder=\"{{lbl.search}}\" /></div></div>",
        select: "<input name=\"select\" type=\"{{ctx.type}}\" class=\"{{css.selectBox}}\" value=\"{{ctx.value}}\" {{ctx.checked}} />"
    }
})
.on("loaded.rs.jquery.bootgrid", function()
{
    /* Executes after data is loaded and rendered */
    grid.find(".command-delete, [data-toggle='confirmation']").on("click", function(event)
    {
        var href = $(this).attr('href');
        var title = $(this).attr('title');
        var text = $(this).data('text');
        var id = $(this).data('row-id');
        var confirmButtonText = $(this).data('btnoklabel');
        var cancelButtonText = $(this).data('btncancellabel');
        event.preventDefault();
        swal({
            title: title,
            text: text,
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: confirmButtonText,
            cancelButtonText: cancelButtonText,
            allowOutsideClick: true,
        },
        function(){
            $.ajax({ url: href,
                }).done(function ( data ) {
                    $("#grid-data-api").bootgrid("reload");
            });
        });
    });

    <?if(core::count($search_fields) == 0):?>
    $('.search.form-group').html('');
    <?endif?>

    $("#grid-data-api tr").removeClass( "success info" )
    //filter_buttons = $('#filter_buttons').html();
    //$('#filter_buttons').html('');
    //$('.actionBar').html(filter_buttons+$('.actionBar').html());
/*    $( '#form-ajax-load').submit(function( event ) {
        event.preventDefault();
        form = $(this);
        pageurl = form.attr('action')+'?'+form.serialize();
        $('#filter_buttons').data('url',pageurl);
        if ( history.replaceState ) history.pushState( {}, document.title, pageurl );
        $("#grid-data-api").bootgrid("reload");
    });*/

});

$('.datepicker_boot').datepicker();

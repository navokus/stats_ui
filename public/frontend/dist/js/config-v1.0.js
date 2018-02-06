$(function () {
	
    $.fn.dataTableExt.sErrMode = 'throw';

    // config calendar compare day
    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

    var checkin = $('#dpd1').datepicker({
        format: 'dd/mm/yyyy',
        onRender: function (date) {
            return date.valueOf() > now.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate', function (ev) {

        if (ev.date.valueOf() < checkout.date.valueOf()) {
            var newDate = new Date(ev.date)
            newDate.setDate(newDate.getDate() - 1);
            checkout.setValue(newDate);
        } else {
            checkout.setValue(checkout.date.valueOf());
        }
        checkin.hide();
        $('#dpd2')[0].focus();
    }).data('datepicker');

    var checkout = $('#dpd2').datepicker({
        format: 'dd/mm/yyyy',
        onRender: function (date) {
            return date.valueOf() > checkin.date.valueOf() - 1 ? 'disabled' : '';
        }
    }).on('changeDate', function (ev) {
        checkout.hide();
    }).data('datepicker');

    // end calendar compare day

    // config select box week
    $('#wpw1').change(function () {
        var curWeek = new Date($(this).val());
        var i = 0;

        var valuePreWeek = $("#wpw2").val();
        $("#wpw2 > option").each(function () {
            var preWeek = new Date(this.value);
            if (preWeek >= curWeek) {
                $(this).css('display', 'none');
                $(this).removeAttr('selected');
            } else {
                if (i == 0) {
                    var datePreWeek = new Date(valuePreWeek);
                    if (datePreWeek >= curWeek) {
                        $(this).attr('selected', 'selected');
                    }
                }
                $(this).css('display', 'block');
                i++;
            }
        });
    });
    $('#wpw1').change();
    // end select box week

    // config select box month
    $('#mpm1').change(function () {
        var curMonth = new Date($(this).val());
        var i = 0;

        var valuePreMonth = $("#mpm2").val();
        var equal = $("#mpm2").hasClass('equal');
        $("#mpm2 > option").each(function () {
            var preMonth = new Date(this.value);

            if (equal) {
                if (preMonth > curMonth) {
                    $(this).css('display', 'none');
                    $(this).removeAttr('selected');
                } else {
                    if (i == 0) {
                        var datePreMonth = new Date(valuePreMonth);
                        if (datePreMonth >= curMonth) {
                            $(this).attr('selected', 'selected');
                        }
                    }
                    $(this).css('display', 'block');
                    i++;
                }
            } else {
                if (preMonth >= curMonth) {
                    $(this).css('display', 'none');
                    $(this).removeAttr('selected');
                } else {
                    if (i == 0) {
                        var datePreMonth = new Date(valuePreMonth);
                        if (datePreMonth >= curMonth) {
                            $(this).attr('selected', 'selected');
                        }
                    }
                    $(this).css('display', 'block');
                    i++;
                }
            }
        });
    });
    $('#mpm1').change();
    // end config select box month

    $('#datepicker').daterangepicker({
        showWeekNumbers: true,
        singleDatePicker: true,
        format: 'YYYY-MM-DD'
    });

    $('#daypicker').daterangepicker({
        showWeekNumbers: true,
        singleDatePicker: true,
        format: 'DD/MM/YYYY'
    });


    // page Promotion Compare
    $('#promo-game').click(function () {
        var code = $(this).val();
        $.get(url + "/Promotion/listPromotionByGame/" + code, function (data) {
            $('#promo-promotion').html(data);
            console.log("done");
        })
    });


    $('[data-toggle="popover"]').popover({
        html: true,
        template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title text-center"></h3><div class="popover-content no-padding"></div></div>'
    });
    $('[data-widget="collapse"]').click(function () {
        $(window).resize();
    });

    $('div[id^="modal_"]').on('shown.bs.modal', function (e) {
        $(window).resize();
    });

    $('#content .collapsed-box:eq(0) .fa-plus').removeClass('fa-plus').addClass('fa-minus');
    $('#content .collapsed-box:eq(0)').removeClass('collapsed-box');

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

        // table compare game in page dashboard
        if (e.currentTarget.hash == '#compare_allGame' && $('#table-compare-all-game').length) {
            if (!$.fn.dataTable.isDataTable('#table-compare-all-game')) {
                // table compare all game
                var table_compare_all_game = $('#table-compare-all-game').DataTable({
                    paging: false,
                    searching: false,
                    pageLength: 1000,
                    ordering: false,
                    info: false,
                    order: [],
                    scrollX: true,
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel'
                    ]
                });
                new $.fn.dataTable.FixedColumns(table_compare_all_game, {
                    //heightMatch: 'none',
                    leftColumns: 2
                });
            }
        }

        // tab retention revenue
        if (e.currentTarget.hash == '#tab_2-2' && $('#table-revenue').length) {

            if (!$.fn.dataTable.isDataTable('#table-revenue')) {
                // table revenue
                var table_revenue = $('#table-revenue').DataTable({
                    responsive: true,
                    paging: false,
                    searching: false,
                    pageLength: 1000,
                    ordering: false,
                    order: [],
                    info: false,
                    //scrollY: false,
                    scrollX: true,
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel'
                    ]
                });
                new $.fn.dataTable.FixedColumns(table_revenue, {
                    heightMatch: 'none',
                    leftColumns: 1
                });
            }
        }


        $(window).resize();
    });

    // table retention revenue in page retention
    if ($('#table-revenue').length) {
        if (!$.fn.dataTable.isDataTable('#table-revenue')) {

            if ($('#table-revenue').hasClass('page-retention')) {
                // table revenue
                var table_revenue = $('#table-revenue').DataTable({
                    responsive: true,
                    paging: false,
                    searching: false,
                    pageLength: 1000,
                    ordering: false,
                    order: [],
                    info: false,
                    //scrollY: false,
                    scrollX: true,
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel'
                    ]
                });
                new $.fn.dataTable.FixedColumns(table_revenue, {
                    heightMatch: 'none',
                    leftColumns: 1
                });
            }
        }
    }

    // table comepare all game page compare
    if ($('#table-compare-all-game').length) {

        if ($('#table-compare-all-game').hasClass('compare-game')) {
            var table_compare_all_game = $('#table-compare-all-game').DataTable({
                paging: false,
                searching: false,
                ordering: false,
                order: [],
                pageLength: 1000,
                info: false,
                scrollX: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel'
                ]

            });
            new $.fn.dataTable.FixedColumns(table_compare_all_game, {
                heightMatch: 'none',
                leftColumns: 2
            });
        }
    }

    if ($('#table-transfer').length) {

        // table transer paying
        var table_transfer = $('#table-transfer').DataTable({
            responsive: true,
            paging: false,
            searching: false,
            pageLength: 1000,
            order: [],
            ordering: false,
            info: false,
            //scrollY: false,
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel'
            ]
        });
        new $.fn.dataTable.FixedColumns(table_transfer, {
            // heightMatch: 'none'
        });

    }

    if ($('#table-transfer-detail').length) {
        var table_transfer_detail = $('#table-transfer-detail').DataTable({
            responsive: true,
            paging: false,
            searching: false,
            order: [],
            ordering: false,
            pageLength: 1000,
            info: false,
            //scrollY: false,
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel'
            ]
        });
        new $.fn.dataTable.FixedColumns(table_transfer_detail, {
            //heightMatch: 'none'
        });
    }


    if ($('#table-revenue2').length) {
        var table_revenue2 = $('#table-revenue2').DataTable({
            responsive: true,
            paging: false,
            order: [],
            searching: false,
            pageLength: 1000,
            ordering: false,
            info: false,
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel'
            ]
        });
    }

    // compare one game dashboard
    $('select[name="gameCodeCompare"]').change(function () {
        var gameCode = $(this).val();

        // check call ajax from Dashboard or Compare Game
        var pageCompareGame = $(this).hasClass('compare-game');
        var url_CompareOneGame = '';
        if (pageCompareGame) {
            // page Compare Game
            url_CompareOneGame = url + "/CompareGame/compareProductOneGame/" + gameCode;
        } else {
            // page Dashboard
            url_CompareOneGame = url + "/Dashboard/compareProductOneGame/" + gameCode;
        }

        $.get(url_CompareOneGame, function (data) {
            $("#rs_compapre_onegame").html(data);
            // data table
            if ($('#table-compare-one-game').length) {
                var table_compare_one_game = $('#table-compare-one-game').DataTable({
                    responsive: true,
                    paging: false,
                    searching: false,
                    order: [],
                    ordering: false,
                    pageLength: 1000,
                    info: false,
                    //scrollY: false,
                    scrollX: true,
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel'
                    ]
                });
                new $.fn.dataTable.FixedColumns(table_compare_one_game, {
                    heightMatch: 'none'
                });
            }

            $(window).resize();
        });
    }).change();
    //$('select[name="gameCodeCompare"]');

    // data table revenue daily
    var table_revenue_daily = $('#table-revenue-daily').DataTable({
        responsive: true,
        paging: true,
        lengthChange: false,
        order: [],
        searching: false,
        pageLength: 1000,
        //ordering: true,
        info: false,
        scrollX: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel'
        ]
    });

    // data table-arppu
    var table_arppu = $('#table-arppu').DataTable({
        paging: true,
        lengthChange: false,
        pageLength: 1000,
        order: [],
        searching: false,
        //ordering: true,
        info: false,
        scrollX: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel'
        ]
    });


    // data active-account

    if ($('#active-account').length) {
        var active_account = $('#active-account').DataTable({
            paging: true,
            lengthChange: false,
            pageLength: 1000,
            searching: false,
            //ordering: true,
            order: [],
            info: false,
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel'
            ]
        });
    }

    function cb(start, end) {
        $('#daterangepicker span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }
    cb(moment().subtract(29, 'days'), moment());

    $('input[name="daterangepicker"]').daterangepicker({
        alwaysShowCalendars:true,
        autoUpdateInput: false,
        locale: {
            format: 'DD/MM/YYYY',
            cancelLabel: 'Clear'
        },
        autoApply:true,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    $('input[name="daterangepicker"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    });

    $('input[name="daterangepicker"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    $('input[name="datesinglepicker"]').daterangepicker({
        autoUpdateInput: false,
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'DD/MM/YYYY',
            cancelLabel: 'Clear'
        },
    });

    $('input[name="datesinglepicker"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY'));
    });


    /*$('#all_kpi tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
    } );
    
    $('#user_kpi tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
    } );
    
    $('#revenue_kpi tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
    } );*/
    
    $('.table tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
    } );

    $('#kpidatepicker').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy'
    }).on('changeDate', function (ev) {
        $('#kpidatepicker').datepicker('hide');
        $('#kpidatepicker').val(this.val());
    });
    
    $(window).resize();
});

$(document).ready(function(){
	
	var amountScrolled = 300;
	// vinhdp
	$(window).scroll(function() {    
	    var scroll = $(window).scrollTop();   
	    var pos = 0;
	    if (scroll <= 50) {

	    	$("#sidebarToggle").offset({ top: 0});
	    }else{
	    	$("#sidebarToggle").offset({ top: 0});
	    }
	    
	    if ( $(window).scrollTop() > amountScrolled ) {
			$('a.back-to-top').fadeIn('slow');
		} else {
			$('a.back-to-top').fadeOut('slow');
		}
	});
	
	$('a.back-to-top').click(function() {
		$('html, body').animate({
			scrollTop: 0
		}, 700);
		return false;
	});
	
	$('input').iCheck({
	    checkboxClass: 'icheckbox_minimal-blue',
	    radioClass: 'iradio_minimal-blue',
	    increaseArea: '20%' // optional
	  });
	
	$("#clearAllCheckBoxes").on("click", function(e){
		$("input[type=checkbox]").iCheck('uncheck');
		e.preventDefault();
	});
	
	$("#groupDownload").on("click", function(e){
		
		$("#selectionForm").append('<input type="hidden" name="isDownload" value="true" id="isDownload"/> ');
		$("#selectionForm").attr('target', '_blank');
		$("#selectionForm").submit();
		$("#selectionForm").attr('target', '');
		$("#isDownload").remove();
		e.preventDefault();
	});
	
	$("#checkAll").on("click", function(e){
		$("input[type=checkbox]").iCheck('check');
		e.preventDefault();
	});

    if ($('#kpi-device-report').length) {
        var exportTitle = $("#kpi-device-report").data("export-title");
        var kpi_device_report = $('#kpi-device-report').DataTable({
            lengthChange: false,
            pageLength: 1000,
            responsive: true,
            fixedColumns:   {
                leftColumns: 1
            },
            searching: false,
            info: false,
            //   scrollX: true,
            scrollY: "500px",
            scrollCollapse: true,
            order: [],
            dom: 'Bfrtip',
            buttons: [
                { extend: 'excel',title: exportTitle},
                'copy'
            ]
        });
    }

    if ($('#kpi-hourly-report').length) {
        var exportTitle = $("#kpi-hourly-report").data("export-title");
        var kpi_hourly_report = $('#kpi-hourly-report').DataTable({
            lengthChange: false,
            pageLength: 1000,
            responsive: true,
            fixedColumns:   {
                leftColumns: 1
            },
            searching: false,
            info: false,
            scrollX: true,
            scrollY: "500px",
            order: [],
            dom: 'Bfrtip',
            buttons: [
                { extend: 'excel',title: exportTitle},
                'copy'
            ]
        });
    }


    if($("#revenue_kpi").length > 0){
    	
    	var exportTitle = $("#revenue_kpi").data("export-title");
    	
        $("#revenue_kpi").DataTable({
            responsive: true,
            paging: false,
            searching: false,
            ordering: false,
            info: false,
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [
  				{ extend: 'excel',title: exportTitle},
				'copy'
            ],
            fixedColumns:   {
                leftColumns: 1
            }
        });
    };
    
    if($("#user_kpi").length > 0){
    	
    	var exportTitle = $("#user_kpi").data("export-title");
    	
        var table = $("#user_kpi").DataTable({
            responsive: true,
            paging: false,
            searching: false,
            ordering: false,
            info: false,
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [
  				{ extend: 'excel',title: exportTitle},
				'copy'
            ],
            fixedColumns:   {
                leftColumns: 1
            }
        });

    };
    
    if($("#all_kpi").length > 0){
    	
    	var exportTitle = $("#all_kpi").data("export-title");
    	
        var table = $("#all_kpi").removeAttr('width').DataTable({
            responsive: true,
            paging: false,
            searching: false,
            ordering: false,
            info: false,
         //   scrollX: true,
            scrollY: "600px",
            scrollCollapse: true,
            dom: 'Bfrtip',
            buttons: [
				{ extend: 'excel',title: exportTitle},
				'copy'
            ],
            fixedColumns:   {
                leftColumns: 1
            }
        });
    };

    if ($('#key-kpi-compare').length) {
        var exportTitle = $("#key-kpi-compare").data("export-title");

        var key_kpi_compare = $('#key-kpi-compare').DataTable({
            lengthChange: false,
            pageLength: 1000,
            responsive: true,
            fixedColumns:   {
                leftColumns: 1
            },
            searching: false,
            info: false,
            scrollCollapse: true,
            order: [],
            dom: 'Bfrtip',
            buttons: [
                { extend: 'excel',title: exportTitle},
                'copy'
            ]
        });
    }

    if ($('#kpi-report-revenue').length) {
    	var exportTitle = $("#kpi-report-revenue").data("export-title");
    	
        var kpi_report_revenue = $('#kpi-report-revenue').DataTable({
            lengthChange: false,
            pageLength: 1000,
            responsive: true,
            ordering: false,
            fixedColumns:   {
                leftColumns: 1
            },
            searching: false,
            info: false,
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [
                { extend: 'excel',title: exportTitle},
                'copy'
            ]
        });
    }
    
    if ($('#kpi-report-active-user').length) {
        var exportTitle = $("#kpi-report-active-user").data("export-title");

        var kpi_report_active_user = $('#kpi-report-active-user').DataTable({
            lengthChange: false,
            pageLength: 1000,
            responsive: true,
            ordering: false,
            fixedColumns:   {
                leftColumns: 1
            },
            searching: false,
            info: false,
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [
                { extend: 'excel',title: exportTitle},
                'copy'
            ]
        });
    }

    var buttonCommon = {
        exportOptions: {
            format: {
                body: function ( data, row, column, node ) {
                    if(row === 0){
                        return data.replace(/<(?:.|\n)*?>/gm, '');
                    }
                    return parseFloat(data.replace( /,/g, '' ));
                }
            }
        }
    };
    /*if ($('#exp_monthly_report').length) {
        var exp_monthly_report = $('#exp_monthly_report').DataTable({
            paging: true,
            searching: false,
            ordering: false,
            order: [],
            pageLength: 20,
            info: false,
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel'
            ]

        });
        new $.fn.dataTable.FixedColumns(exp_monthly_report, {
            heightMatch: 'none',
            leftColumns: 1
        });

    }*/
    if ($('#marketing_report').length) {
        var marketing_report = $('#marketing_report').DataTable({
            paging: true,
            searching: false,
            ordering: false,
            order: [],
            pageLength: 20,
            info: false,
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel'
            ]

        });
        new $.fn.dataTable.FixedColumns(marketing_report, {
            heightMatch: 'none',
            leftColumns: 2
        });

    }
    if ($('#server_test_report').length) {
        var exportTitle = $("#server_test_report").data("export-title");
        var marketing_report = $('#server_test_report').DataTable({
            paging: true,
            searching: false,
            ordering: true,
            order: [],
            pageLength: 20,
            info: false,
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [
                {extend: 'excel',title: exportTitle},
                'copy'
            ],
        });
        new $.fn.dataTable.FixedColumns(marketing_report, {
            heightMatch: 'none',
            leftColumns: 2
        });

    }

    if ($('#rev_report').length) {
        var topowner_report = $('#rev_report').DataTable({
            paging: true,
            searching: false,
            ordering: false,
            order: [],
            pageLength: 30,
            info: false,
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel'
            ]

        });
        new $.fn.dataTable.FixedColumns(topowner_report, {
            heightMatch: 'none',
            leftColumns: 2
        });

    }
    if ($('#a1_report').length) {
        var topowner_report = $('#a1_report').DataTable({
            paging: true,
            searching: false,
            ordering: false,
            order: [],
            pageLength: 30,
            info: false,
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel'
            ]

        });
        new $.fn.dataTable.FixedColumns(topowner_report, {
            heightMatch: 'none',
            leftColumns: 2
        });

    }
    if ($('#kpi_table').length) {
        var exportTitle = $("#kpi_table").data("export-title");

        var kpi_report_export = $('#kpi_table').removeAttr('width').DataTable({
            lengthChange: false,
            pageLength: 1000,
            paging:false,

            responsive: true,
            ordering: false,
            fixedColumns:   {
                leftColumns: 1
            },
            searching: true,
            info: false,
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [
                $.extend( true, {}, buttonCommon, {
                    extend: 'excel',title: exportTitle
                } )
                /*
                 {
                 extend: 'excel',
                 title: exportTitle
                 }
                 */
            ],
            columnDefs: [
                { width: 120, targets: 0 }
            ],
        });
    }

    if ($('#Daily_report').length) {
        var exportTitle = $("#mobile_report").data("export-title");
        var mobile_report = $('#mobile_report').DataTable({
            paging: true,
            searching: true,
            ordering: false,
            order: [],
            pageLength: 15,
            scrollX:true,
            /* scrollX:true,*/
            info: false,
            dom: 'Bfrtip',
            buttons: [
                {extend: 'excel',title: exportTitle},
                'copy'
            ],
        });
        new $.fn.dataTable.FixedColumns(mobile_report, {
            heightMatch: 'none',
        });

    }

    if ($('#Weekly_report').length) {
        var exportTitle = $("#Weekly_report").data("export-title");
        var daily_report = $('#Weekly_report').DataTable({
            paging: true,
            searching: true,
            ordering: false,
            order: [],
            pageLength: 15,
            info: false,

            dom: 'Bfrtip',
            buttons: [
                {extend: 'excel',title: exportTitle},
                'copy'
            ],
        });
        new $.fn.dataTable.FixedColumns(daily_report, {
            heightMatch: 'none',
        });

    }

    if ($('#Monthly_report').length) {
        var exportTitle = $("#Monthly_report").data("export-title");
        var daily_report = $('#Monthly_report').DataTable({
            paging: true,
            searching: true,
            ordering: false,
            order: [],
            pageLength: 15,
            info: false,

            dom: 'Bfrtip',
            buttons: [
                {extend: 'excel',title: exportTitle},
                'copy'
            ],
        });
        new $.fn.dataTable.FixedColumns(daily_report, {
            heightMatch: 'none',
            leftColumns: 1
        });

    }

    if ($('#mobile_report').length) {
        var exportTitle = $("#mobile_report").data("export-title");
        var mobile_report = $('#mobile_report').DataTable({
            paging: true,
            searching: true,
            ordering: false,
            order: [],
            pageLength: 15,
            scrollX:true,
            /* scrollX:true,*/
            info: false,
            dom: 'Bfrtip',
            buttons: [
                {extend: 'excel',title: exportTitle},
                'copy'
            ],
        });
        new $.fn.dataTable.FixedColumns(mobile_report, {
            heightMatch: 'none',
        });

    }

    if ($('#channel_report').length) {
        var exportTitle = $("#channel_report").data("export-title");
        var daily_report = $('#channel_report').removeAttr('width').DataTable({
            paging: true,
            searching: true,
            ordering: false,
            order: [],
            pageLength: 15,
            scrollX:true,
            /* scrollX:true,*/
            info: false,
            dom: 'Bfrtip',
            buttons: [
                {extend: 'excel',title: exportTitle},
                'copy'
            ],
        });
        new $.fn.dataTable.FixedColumns(daily_report, {
            heightMatch: 'none',
        });

    }


    if ($('#kpi-report-export').length) {
    	var exportTitle = $("#kpi-report-export").data("export-title");

        var kpi_report_export = $('#kpi-report-export').removeAttr('width').DataTable({
            lengthChange: false,
            pageLength: 1000,
            paging:false,

            responsive: true,
            ordering: false,
            fixedColumns:   {
                leftColumns: 1
            },
            searching: true,
            info: false,
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [
                $.extend( true, {}, buttonCommon, {
                    extend: 'excel',title: exportTitle
                } )
/*
				{
                    extend: 'excel',
                    title: exportTitle
                }
                */
            ],
            columnDefs: [
                         { width: 120, targets: 0 }
                       ]
        });
    }

    $('.buttons-excel').each(function() {
    	$(this).attr("title", "Download table as excel file!");
    	$(this).html("<i class='fa fa-file-excel-o' aria-hidden='true'></i>");
    });
    $('.buttons-copy').each(function() {
    	$(this).attr("title", "Copy table to clipboard!");
 		$(this).html("<i class='fa fa-copy' aria-hidden='true'></i>");
	});
	$('.buttons-print').each(function() {
		$(this).attr("title", "Print table!");
    	$(this).html("<i class='fa fa-print' aria-hidden='true'></i>");
    });
    $('.buttons-csv').each(function() {
    	$(this).attr("title", "Download table as csv file!");
    	$(this).html("<i class='fa fa-file-text-o' aria-hidden='true'></i>");
    });

	$("#slGame").select2();
    $("#slGameSelection").select2();
    $("#slServerSelection").select2();
	$("#slTiming").select2({
		minimumResultsForSearch: Infinity
	});

	$("#wpw2").select2({
		minimumResultsForSearch: Infinity
	});
	$("#wpw1").select2({
		minimumResultsForSearch: Infinity
	});
	$("#mpm2").select2({
		minimumResultsForSearch: Infinity
	});
	$("#mpm1").select2({
		minimumResultsForSearch: Infinity
	});
	
	// event select time
    //$('select[name="options"]').change(function () {
    	
        var value = $('select[name="options"]').val();
        $('.option_time').addClass('hide');
        switch (value) {
            case '4' :
                $('.option_day').removeClass('hide');
                break;
            case '5' :
                $('.option_day').removeClass('hide');
                break;
            case '6' :
                $('.option_day').removeClass('hide');
                break;
            case '17' :
                $('.option_week').removeClass('hide');
                break;
            case '31' :
                $('.option_month').removeClass('hide');
                break;
            case '1' :
            case '2' :
            case '3' :
            default:
                $('.option_disable').removeClass('hide');
                break;

        }
    //});
    
    //$('select[name="options"]').change();

    $("#copy").on("click", function(e){
    	e.preventDefault()
    	if($(".tab-pane.active .buttons-copy").length > 0){
    		$(".tab-pane.active .buttons-copy").trigger("click");
    	} else {
    		$(".buttons-copy").trigger("click");
    	}
    });
	
    $("#dashboard-overview-choose").multipleSelect({
        width: 320,
        multiple: true,
        selectAll: false,
        placeholder: "Select ...",
        multipleWidth: 100
    });
    $("#dashboard-trend-chart-1-choose").multipleSelect({
        width: 320,
        multiple: true,
        selectAll: false,
        placeholder: "Select ...",
        multipleWidth: 100,
        onClick: function(view) {
            var $checkboxes = $("#dashboard-trend-chart-1-choose").next().find("input[type='checkbox']").not(":checked");
            var selectedLen = $("#dashboard-trend-chart-1-choose").multipleSelect('getSelects').length;
            if (selectedLen >= 6) {
                $checkboxes.prop("disabled", true);
            } else {
                $checkboxes.prop("disabled", false);
            }
        }
    });

    $("#dashboard-trend-chart-2-choose").multipleSelect({
        width: 320,
        multiple: true,
        selectAll: false,
        placeholder: "Select ...",
        multipleWidth: 100,
        onClick: function(view) {
            var $checkboxes = $("#dashboard-trend-chart-2-choose").next().find("input[type='checkbox']").not(":checked");
            var selectedLen = $("#dashboard-trend-chart-2-choose").multipleSelect('getSelects').length;
            if (selectedLen >= 6) {
                $checkboxes.prop("disabled", true);
            } else {
                $checkboxes.prop("disabled", false);
            }
        }
    });
    
    
    /**
     * Menu Highlight
     */
    var pgurl = location.pathname;
    //console.log(pgurl);
	var prefix = '';
	
	$("ul.sidebar-menu li ul li").each(function(){
		$(this).removeClass("active");
	});
	
	$("ul.sidebar-menu li ul li a").each(function() {
			
		var itemUrl = $(this).attr("href").replace(prefix, "");
		//console.log(itemUrl);
		if (itemUrl == pgurl || itemUrl == '/' || itemUrl.indexOf(pgurl) > -1)
			$(this).parent().addClass("active");
	})
	
	/**
	 * Jump to chart
	 */
	
	$(".revenue-chart").click(function(event){
         event.preventDefault();
         //calculate destination place
         var dest=0;
         var hash = "#section-revenue";
         if($(hash).offset().top > $(document).height()-$(window).height()){
              dest=$(document).height()-$(window).height();
         }else{
              dest=$(hash).offset().top;
         }
         //go to destination
         $('html,body').animate({scrollTop:dest}, 1000,'swing');
    });
	
	$(".user-chart").click(function(event){
        event.preventDefault();
        //calculate destination place
        var dest=0;
        var hash = "#section-user";
        if($(hash).offset().top > $(document).height()-$(window).height()){
             dest=$(document).height()-$(window).height();
        }else{
             dest=$(hash).offset().top;
        }
        //go to destination
        $('html,body').animate({scrollTop:dest}, 1000,'swing');
   });
	
	$(".games-menu-control").on("click", function(e){
		var value = $(this).data("value");
		$("#gameType").val(value);
		$(this).closest('form').submit();
	});
	
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

		/*for (i = 0; i < Highcharts.charts.length; i = i + 1) {
	        chart = Highcharts.charts[i];
	        chart.reflow();
	        console.log(i);
		}*/
		window.dispatchEvent(new Event('resize'));
    });
	
	/**
	 * Package KPI JS
	 */
	 
	if($("#package-detail").length > 0){
		var exportTitle = $("#package-detail").data("export-title");
		
        var table = $("#package-detail").DataTable({
        	responsive: true,
            paging: false,
            searching: false,
            ordering: false,
            order: [[ 1, "desc" ]],
            info: false,
            scrollX: true,
            scrollY: "600px",
            autoWidth: false,
            dom: 'Bfrtip',
            buttons: [
				{ extend: 'excel',title: exportTitle},
				'copy'
            ],
            fixedColumns:   {
                leftColumns: 1
            }
        });

    };
    
    /**
	 * Channel KPI JS
	 */
	 
	if($("#channel-detail").length > 0){
		var exportTitle = $("#channel-detail").data("export-title");
		
        var table = $("#channel-detail").DataTable({
            responsive: true,
            paging: false,
            searching: false,
            ordering: false,
            order: [[ 1, "desc" ]],
            info: false,
   //         scrollX: true,
            scrollY: "600px",
            dom: 'Bfrtip',
            buttons: [
				{ extend: 'excel',title: exportTitle},
				'copy'
            ],
            fixedColumns:   {
                leftColumns: 1
            }
        });

    };
	
    if($("#package").length > 0 || $("#channel").length > 0){
		drawChart(1);
		
		$(".nav-tabs a").on("click", function(){
			var time = $(this).data("id");
			drawChart(time);
		});
	}
    
    /**
     * Top Server
     */
    /*if($("#group").length > 0){
    	var exportTitle = $("#group").data("export-title");
    	
        var table = $("#group").DataTable({
            responsive: true,
            paging: false,
            searching: false,
            ordering: false,
            order: [[ 1, "desc" ]],
            info: false,
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [
				{ extend: 'excelHtml5',title: exportTitle},
				'copy'
            ],
            fixedColumns:   {
                leftColumns: 1
            }
        });

    };*/
    
	$(".dt-buttons").addClass("hidden");
	$("#downloadExcel").on("click", function(e){
    	e.preventDefault()
    	if($(".tab-pane.active .buttons-excel").length > 0){
    		console.log("download excel tab")
    		$(".tab-pane.active .buttons-excel").trigger("click");
    	} else {
    		console.log("download excel")
    		$(".buttons-excel").trigger("click");
            //$('title').html(oldTitle);
    	}
    });









	$("#report-top-pc-games").on("click", function(e){
		renderChartTopPC();
	});
	
	$("#report-top-mobile-games").on("click", function(e){
		renderChartTopMobile();
	});
	
	$("#report-top-all-games").on("click", function(e){
		renderChartTopAll();
	});
	
	/*$("#downloadExcelv2").on("click", function(e){
    	e.preventDefault();
    	tableToExcel('group');
    });*/
	
	

	$('.show_hide').click(function(){
		$(".expand-btn").toggleClass("hidden");
		$(".sliding").toggleClass("open", 'slow', "easeOutSine");
	});
	
	//dashboardclick
	$(".dbrclk").on("click", function(e){
	var timing = $(this).attr('data-id');
		renderDashboard2(timing);
	});
	
	/*$(".tab-selection").on("click", function(e){
		var id = $(this).children("a").data("id");
		$("#tab_" + id).find(".chart-area").each( function( index, element ){
		    
		    var fname = $( this ).attr("id");
		    console.log("Calling draw function: " + fname );
		    var fn = window[fname];
		    fn();
		});
	});*/
	
	//kpicomparison click
	$(".kpicpclk").on("click", function(e){
	var timing = $(this).attr('data-id');
		renderDashboard2(timing);
	});
	
	if($("#group-table").length > 0){
		$(".nav-tabs a").on("click", function(){
			var tabId = $(this).data("id");
			var groupId = $("#chart-data").data("group");
			var timing = $("#chart-data").data("timing");
			//console.log(tabId);
			//console.log(timing);
			$("#tab_" + tabId).find(".fa-spinner:not(:first)").each(function(){
				$(this).hide();
			});
			$("#tab_" + tabId).find(".chart-area").each(function(){
				var kpiName = $(this).data("name");
				var isRendered = $(this).data("is-render");
				if(isRendered){
					//console.log("no action");
				}else{
					//console.log(kpiName);
					$("#" + kpiName).html('<i class="fa fa-spinner fa-spin fa-4x"></i>');
					$.ajax({
					    url: url + '/ajax/group/' + groupId + '/' + timing + '/' + kpiName,
					    dataType: 'text',
					    success: function(data) {
					    	//console.log(data);
					    	$("#" + kpiName).html(data);
					    },
					    complete: function() {
					      //console.log("OK");
					      $(".data-alert:not(:first)").hide();
					      console.log("ajax end");
					    }
					});
					
					$(this).data("is-render", "true");
				}
			})
		});
		var timing = $("#chart-data").data("timing");
		// trigger click for first time after load
		if(timing == "17"){
			$('.nav-tabs a[href="#tab_activew"]').trigger("click");
		} else if(timing == "31"){
			$('.nav-tabs a[href="#tab_activem"]').trigger("click");
		} else {
			$('.nav-tabs a[href="#tab_active1"]').trigger("click");
		}
	};
	
	if($("#group-table").length > 0){

		var groupId = $("#chart-data").data("group");
		var timing = $("#chart-data").data("timing");
		
		$("#group-table").html('<i class="fa fa-spinner fa-spin fa-4x"></i>');
		$.ajax({
		    url: url + '/ajax/group-table/' + groupId + '/' + timing,
		    dataType: 'text',
		    success: function(data) {
		    	
		    	$("#group-table").html(data);
		    },
		    complete: function() {
		      
		      console.log("ajax table end");
		    }
		});
	};
});

function convertData(groupId, from, to){
	var start = new Date(from);
    var end = new Date(to);
    
    while(start < end){

        var date = start.toISOString().slice(0,10);
        
        $.ajax({
    	    url: url + '/ajax/convert/' + groupId + '/' + date,
    	    dataType: 'text',
    	    success: function(data) {
    	    	console.log(date + ": " + data);
    	    },
    	    complete: function() {
    	      
    	    }
    	});
        
        var newDate = start.setDate(start.getDate() + 1);
        start = new Date(newDate);
     }
    console.log("END");
}

var tableToExcel = (function () {
	
    var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
    , base64 = function (s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function (s, c) { return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; }) }
    return function (table) {
        if (!table.nodeType) table = document.getElementById(table);
        var ctx = { worksheet: name || 'Worksheet', table: table.innerHTML }

        document.getElementById("dlink").href = uri + base64(format(template, ctx));
        document.getElementById("dlink").click();
    }
})();

function registerBtnDatatableEvent(){
	/*$(".dt-buttons").addClass("hidden");
	$("#downloadExcel").on("click", function(e){
      	e.preventDefault();
      	console.log("click");
      	if($(".tab-pane.active .buttons-excel").length > 0){
      		console.log("download excal");
      		$(".tab-pane.active .buttons-excel").trigger("click");
      	} else {
      		var oldTitle = $(document).attr('title');
              var exportFileName = $("#exportFileName").text()
              if(exportFileName=="")
              {
                  exportFileName = oldTitle
              }
              $('title').html(exportFileName);
      		console.log("download excal");
      		$(".buttons-excel").trigger("click");
              //$('title').html(oldTitle);
      	}
      });
	
	$("#copy").on("click", function(e){
    	e.preventDefault()
    	if($(".tab-pane.active .buttons-copy").length > 0){
    		$(".tab-pane.active .buttons-copy").trigger("click");
    	} else {
    		$(".buttons-copy").trigger("click");
    	}
    });*/
	
	$(document).on("click", "#downloadExcel", function(e){
    	e.preventDefault()
    	if($(".tab-pane.active .buttons-excel").length > 0){
    		console.log("download excel tab 1")
    		$(".tab-pane.active .buttons-excel").trigger("click");
    	} else {
    		console.log("download excel 2")
    		$(".buttons-excel").trigger("click");
            //$('title').html(oldTitle);
    	}
    });
	
	$(document).on("click", "#copy", function(e){
    	e.preventDefault()
    	if($(".tab-pane.active .buttons-copy").length > 0){
    		$(".tab-pane.active .buttons-copy").trigger("click");
    	} else {
    		$(".buttons-copy").trigger("click");
    	}
    });
}

function renderGameGroupReport(group){
	var lid='#lid-'+group;
	var gid='#gid-'+group;
	var tid='#tid-'+group;
	selectedTab = group;
	var tabContent = 'div#tid-'+selectedTab +'.tab-pane';
	$(lid).show();
	$.ajax({
	    url: api_url +'/' + group +'/' + selectedMonth,
	    success: function(data) {
	      $(tabContent).html(data);
	    },
	    complete: function() {
	      //setTimeout(doSlide, 60000);
	    	//alert(selectedMonth);
	    }
	  });
}

function renderChartTopPC(){
	$('#report-top-pc-games-loading').show();
	$.ajax({
	    url: '/index.php/TopKpi/renderPcTop', 
	    success: function(data) {
	      $('#tab_report-top-pc-games').html(data);
	      var exportTitle = $("#game-list-table-pc").data("export-title");
	      console.log(exportTitle);
	      $('#game-list-table-pc').DataTable( {
	    	  paging:   false,
	          searching:   false,
	          ordering: false,
	          info:     false,
	          dom: 'Bfrtip',
	          buttons: [
						{ extend: 'excel', title: exportTitle},
						'copy', 'csv'
	               ]
	      } );
	      registerBtnDatatableEvent();
	    },
	    complete: function() {
	      //setTimeout(doSlide, 60000);
	    }
	  });
	
}
function renderChartTopMobile(){
	$('#report-top-mobile-games-loading').show();
	
	$.ajax({
	    url: '/index.php/TopKpi/renderMobileTop', 
	    success: function(data) {
	      $('#tab_report-top-mobile-games').html(data);
	      var exportTitle = $("#game-list-table-mobile").data("export-title");
	      console.log(exportTitle);
	      $('#game-list-table-mobile').DataTable( {
	          paging:   false,
	          searching:   false,
	          ordering: false,
	          info:     false,
	          dom: 'Bfrtip',
	          buttons: [
						{ extend: 'excel',title: exportTitle},
						'copy', 'csv'
	               ]
	      } );
	      
	      registerBtnDatatableEvent();
	    },
	    complete: function() {
	      //setTimeout(doSlide, 60000);
	    }
	  });
	
}
function renderChartTopAll(){
	$('#report-top-all-games-loading').show();
	
	$.ajax({
	    url: '/index.php/TopKpi/renderAllTop', 
	    success: function(data) {
	      $('#tab_report-top-all-games').html(data);
	      var exportTitle = $("#game-list-table-all").data("export-title");
	      console.log(exportTitle);
	      $('#game-list-table-all').DataTable( {
	          paging:   false,
	          searching:   false,
	          ordering: false,
	          info:     false,
	          dom: 'Bfrtip',
	          buttons: [
	                    { extend: 'excel',title: exportTitle},
	                    'copy', 'csv'
	               ]
	      } );
	      
	      registerBtnDatatableEvent();
	    },
	    complete: function() {
	      //setTimeout(doSlide, 60000);
	    }
	  });
	
}
function drawChart(time){
	var trigger1 = false;
	var trigger7 = false;
	var trigger30 = false;
	
	if(time == 1 && !trigger1){
		if($("#container_a" + time).length > 0){
			drawPieChartcontainer_a1();
		}
		if($("#container_pu" + time).length > 0){
			drawPieChartcontainer_pu1();
		}
		if($("#container_gr" + time).length > 0){
			drawPieChartcontainer_gr1();
		}
		if($("#container_n" + time).length > 0){
			drawPieChartcontainer_n1();
		}
		if($("#container_npu" + time).length > 0){
			drawPieChartcontainer_npu1();
		}
		if($("#container_npu_gr" + time).length > 0){
			drawPieChartcontainer_npu_gr1();
		}
		trigger1 = true;
	}else if(time == 7 && !trigger7){
		if($("#container_a" + time).length > 0){
			drawPieChartcontainer_a7();
		}
		if($("#container_pu" + time).length > 0){
			drawPieChartcontainer_pu7();
		}
		if($("#container_gr" + time).length > 0){
			drawPieChartcontainer_gr7();
		}
		if($("#container_n" + time).length > 0){
			drawPieChartcontainer_n7();
		}
		if($("#container_npu" + time).length > 0){
			drawPieChartcontainer_npu7();
		}
		if($("#container_npu_gr" + time).length > 0){
			drawPieChartcontainer_npu_gr7();
		}
		trigger7 = true;
	}else if( time == 30 && !trigger30){
		if($("#container_a" + time).length > 0){
			drawPieChartcontainer_a30();
		}
		if($("#container_pu" + time).length > 0){
			drawPieChartcontainer_pu30();
		}
		if($("#container_gr" + time).length > 0){
			drawPieChartcontainer_gr30();
		}
		if($("#container_n" + time).length > 0){
			drawPieChartcontainer_n30();
		}
		if($("#container_npu" + time).length > 0){
			drawPieChartcontainer_npu30();
		}
		if($("#container_npu_gr" + time).length > 0){
			drawPieChartcontainer_npu_gr30();
		}
		
		trigger30 = true;
	}
}
function pausecomp(millis)
{
    var date = new Date();
    var curDate = null;
    do { curDate = new Date(); }
    while(curDate-date < millis);
}

function showHide(shID) {
   if (document.getElementById(shID)) {
      if (document.getElementById(shID+'-show').style.display != 'none') {
         document.getElementById(shID+'-show').style.display = 'none';
         document.getElementById(shID).style.display = 'block';
      }
      else {
         document.getElementById(shID+'-show').style.display = 'inline';
         document.getElementById(shID).style.display = 'none';
      }
   }
}

function renderDashboard2(tm){
	timing = tm;
	var loading ='#loading_' + timing;
	$(loading).show();
	$.ajax({
	   url: api_url +'/'+timing,
	   success: function(data) {
	    var tid ="#tab_t"+timing;
	     $(tid).html(data);
	   },
	   complete: function() {
	     //setTimeout(doSlide, 60000);
	   }
	 });
	}
function renderKpiComparison(){
	
var loading ='#loading_comparison';
var elm = document.getElementById("selection_kpi_comparison");
var kpis = elm.options[elm.selectedIndex].value;
console.log(kpis);
$(loading).show();
$.ajax({
   url: api_url +'/'+kpis +'/' + chart,
   success: function(data) {
     $("#content").html(data);
   },
   complete: function() {
     //setTimeout(doSlide, 60000);
   }
 });
}

function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
    return value;
};

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
};
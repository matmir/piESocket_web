$(document).ready(function(){
    
    // Date pickers
    $( "#rangeDateFrom" ).datepicker({
        dateFormat: "yy-mm-dd"
    });
    $( "#rangeDateTo" ).datepicker({
        dateFormat: "yy-mm-dd"
    });
    $( "#currentDataFrom" ).datepicker({
        dateFormat: "yy-mm-dd"
    });
    
    // Time pickers
    $('#rangeTimeFrom').timepicker({
        'timeFormat': 'H:i:s'
    });
    $('#rangeTimeTo').timepicker({
        'timeFormat': 'H:i:s'
    });
    $('#currentTimeFrom').timepicker({
        'timeFormat': 'H:i:s'
    });
    
    var config = {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'My First dataset',
                backgroundColor: [
                    'rgb(54, 162, 235)'
                ],
                borderColor: [
                    'rgb(54, 162, 235)'
                ],
                data: [],
                fill: false
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Test Line Chart'
            },
            tooltips: {
                mode: 'index',
                intersect: false
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                xAxes: [{
                    type: 'time',
                    time: {
                        parser: 'YYYY-MM-DD h:mm:ss.SSS',
                        tooltipFormat: 'YYYY-MM-DD h:mm:ss.SSS'
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Date'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Value'
                    }
                }]
            }
        }
    };
    
    var ctx = document.getElementById('canvasChart').getContext('2d');
    var myChart = new Chart(ctx, config);
    
    // Get chart data
    getData();
    
    // Get chart data request
    function getData() {
        
        // Sorting mode
        var sortMode = $('#sortingMode').val();
        
        // LoggerID
        var loggerID = $('#loggerID').val();
        
        // POST params
        var params = {
            'loggerID': loggerID,
            'sortMode': sortMode,
            'sortData': {}
        };
        
        // Update POST params
        if (sortMode === 'rangeData') {
            
            var rParams = {
                'dateFrom': $('#rangeDateFrom').val(),
                'timeFrom': $('#rangeTimeFrom').val(),
                'dateTo': $('#rangeDateTo').val(),
                'timeTo': $('#rangeTimeTo').val()
            };
            
            params.sortData = rParams;
            
        } else if (sortMode === 'currentData') {
            
            var cParams = {
                'dateFrom': $('#currentDataFrom').val(),
                'timeFrom': $('#currentTimeFrom').val()
            };
            
            params.sortData = cParams;
            
        }
        
        // Send request
        $.post("/admin/chart/get", { "json" : JSON.stringify(params)}, getDataFB);

    }
    
    // Get chart data response
    function getDataFB(qData, status) {
        
        if (status === "success" && qData.error.state === false) {
                        
            if (qData.reply.chartType === 'stepped') {
                config.data.datasets[0].steppedLine = 'before';
            }
            
            config.options.title.text = qData.reply.chartTitle;
            config.data.datasets[0].label = qData.reply.dataTitle;
            config.data.labels = qData.reply.data.x;
            config.data.datasets[0].data = qData.reply.data.y;

            // Update chart
            myChart.update();
            
            // Enable buttons
            $("#lastDataBtn").attr("disabled", false);
            $("#rangeDataBtn").attr("disabled", false);
            $("#currentDataBtn").attr("disabled", false);
            
            $('#dangerAlert').hide();

        } else {
            //console.log(qData.error);
            $('#dangerAlert').text(qData.error.msg);
            $('#dangerAlert').show();
        }
        
    }
    
    // Change sorting mode
    $('#sortingMode').on('change', function() {
        
        if (this.value === 'lastData') {
            $('#lastDataControl').show();
            $('#rangeDataControl').hide();
            $('#currentDataControl').hide();
        } else if (this.value === 'rangeData') {
            $('#lastDataControl').hide();
            $('#rangeDataControl').show();
            $('#currentDataControl').hide();
        } else if (this.value === 'currentData') {
            $('#lastDataControl').hide();
            $('#rangeDataControl').hide();
            $('#currentDataControl').show();
        }
    });
    
    // Refresh button click on last data control
    $("#lastDataBtn").click(function(){
        
        $("#lastDataBtn").attr("disabled", true);
        // Get chart data
        getData();
        
    });
    
    // Refresh button click on range data control
    $("#rangeDataBtn").click(function(){
        
        $("#rangeDataBtn").attr("disabled", true);
        // Get chart data
        getData();
    });
    
    // Refresh button click on current data control
    $("#currentDataBtn").click(function(){
        
        $("#currentDataBtn").attr("disabled", true);
        // Get chart data
        getData();
    });
    
    // Axis X scale
    $("#timeScale").click(function(){
                
        if ($('#timeScale').is(":checked"))
        {
            config.options.scales.xAxes = [{
                type: 'time',
                time: {
                    parser: 'YYYY-MM-DD h:mm:ss.SSS',
                    tooltipFormat: 'YYYY-MM-DD h:mm:ss.SSS'
                },
                scaleLabel: {
                    display: true,
                    labelString: 'Date'
                }
            }];
        } else {
            config.options.scales.xAxes = [{
                display: true,
                scaleLabel: {
                    display: true,
                    labelString: 'Date'
                }
            }];
        }
        
        // Update chart
        myChart.update();
        
    });
    
    function unlockCurrentControl() {
        
        $("#currentDataBtn").attr("disabled", false);
        $('#dangerAlert').hide();
        
    }
    
    function unlockRangeControl() {
        
        $("#rangeDataBtn").attr("disabled", false);
        $('#dangerAlert').hide();
        
    }
    
    $('#rangeDateFrom').on('change', function() {
        unlockRangeControl();
    });
    $('#rangeTimeFrom').on('change', function() {
        unlockRangeControl();
    });
    $('#rangeDataTo').on('change', function() {
        unlockRangeControl();
    });
    $('#rangeTimeTo').on('change', function() {
        unlockRangeControl();
    });
    
    $('#currentDataFrom').on('change', function() {
        unlockCurrentControl();
    });
    $('#currentTimeFrom').on('change', function() {
        unlockCurrentControl();
    });
    
});
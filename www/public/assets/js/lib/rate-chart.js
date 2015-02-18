$(function() {
    var previousPoint = null;
    $('#charter').bind('plothover', function(event, pos, item) {
        if ( ! item) {
            $('#tooltip').remove();
            previousPoint = null;
            return false;
        }

        if (previousPoint != item.dataIndex) {
            previousPoint = item.dataIndex;
            
            $("#tooltip").remove();
            var x = item.datapoint[0];
                y = item.datapoint[1].toFixed(4);
            var d = new Date(parseInt(x));

            showTooltip(item.pageX, item.pageY,
                        d.getFullYear() + "-" + ("0"+(d.getMonth()+1)).slice(-2) + "-" + ("0"+d.getDate()).slice(-2) + " " + '：' + y);  
        }      
    })
})

function getSymbol()
{
    return $('#currency_have').val() + $('#currency_want').val();
}

function Chart(){

    this.data = [];
}

// 获得图表数据
Chart.prototype.getData = function(symbol) {
    
    this.symbol = symbol;

    var self = this;

   $.ajax({
        url : '/quote/history?symbol=' + symbol,
        cached : false,
        dataType : 'json',
        success : function(rep) {

            self.data = [];

            $.each(rep.data, function(i, rate) {  
                var rate = [rate.time, rate.rate];
                self.data.push(rate);
            });

            self.show();
        },
        beforeSend : function() {
            $.plot($('#charter'), []);
        }
    });

};

// 显示图表
Chart.prototype.show = function() {

    var dataset = [
        { label : this.symbol, data : this.data }
    ];

    var options = {
        points : { 
            show : true 
        },
        lines : {
            show : true
        },
        xaxis : {
            mode : 'time',
            timeformat : '%d/%m'
        },
        grid : {
            hoverable : true
        }
    };

    $.plot($('#charter'), dataset, options);
}

/**
 * 显示 tooltip
 *
 * @param  int
 * @param  int
 * @param  string
 * @retrun void
 */
function showTooltip(x, y, contents) {
    $('<div id="tooltip">' + contents + '</div>').css( {
        position: 'absolute',
        display: 'none',
        top: y + 5,
        left: x + 5,
        border: '1px solid #fdd',
        padding: '2px',
        'background-color': '#fee',
        opacity: 0.80
    }).appendTo("body").fadeIn(200);    
}


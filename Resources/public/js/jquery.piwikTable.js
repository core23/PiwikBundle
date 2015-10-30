/*!
 * ni-ju-san CMS v1.0.0 (http://ni-ju-san.de)
 * Copyright 2014-2015 Core23 - webdesign & more
 */
(function ($) {
    $.piwikTable = {version: '1.00'};

    $.fn.piwikTable = function () {
        return $(this).each(function () {
            var table = $('table', this);

            var labels = [];
            var data = [];

            $('thead tr:first', table).children().each(function (index) {
                if (index > 0) {
                    data.push([]);
                }
            });

            $('tbody tr', table).each(function () {
                var cells = $(this).children();
                cells.each(function (index) {
                    if (index > 0)
                        data[index - 1].push(parseInt($(this).text()));
                    else
                        labels.push($(this).text());
                });
            });

            var id = 'chartist-' + Math.floor(Math.random() * 99999);
            var div = $('<div>').attr({class: 'ct-chart', id: id});

            table.hide().after(div);

            new Chartist.Line('#' + id, {
                labels: labels,
                series: data
            }, {
                axisX: {
                    labelInterpolationFnc: function (value, index) {
                        return index % 7 === 0 ? '' + value : null;
                    }
                },
                height: 200
            });
        });
    };

    // auto-initialize plugin
    $(function () {
        $('[data-piwik]').piwikTable();
    });
})(jQuery);
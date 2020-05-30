function chart_pie(coverid, percentage, color) {
    var percent_expld = percentage.split(',');
    var color_expld = color.split(',');
    var sum = 0;
    for (var s = 0; s < percent_expld.length; s++) {
        sum += parseInt(percent_expld[s]);
    }
    var each_percent = '';
    for (var s = 0; s < percent_expld.length; s++) {
        each_percent += Math.round((100 / sum) * parseInt(percent_expld[s])) + ",";
    }
    var each_split = each_percent.split(",");
    var pie_paint = 0;
    var pie_color = 0;
    var pie_each_selector = 0;
    var pie_color_selector = 0;
    var buffer = 0;
    var change_color = 0;

    for (var p = 0; p < 100; p++) {
        pie_paint = p + 1;
        pie_color = color_expld[pie_color_selector];
        $("#" + coverid + " #p" + pie_paint).css("fill", pie_color);

        change_color = buffer + parseInt(each_split[pie_each_selector]);
        if (p === parseInt(change_color)) {
            pie_each_selector++;
            pie_color_selector++;
            buffer = change_color;
        }
    }
}
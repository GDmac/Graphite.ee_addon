
// Load the Visualization API and the piechart package.
google.load('visualization', '1.0', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
$(window).load(function(){
  drawChart();
});

function drawChart() {

    var data = getLogData();

    var options = {
        curveType: "none", 
        height: 400,
        backgroundColor : '#ededed',
        fontSize : 11 };


    var chart = new google.visualization.LineChart(document.getElementById('graphite_log_graph_holder'));
    chart.draw(data, options);
}


function getLogData() {

    var data = new google.visualization.DataTable();

    data.addColumn('string', 'x');
    data.addColumn('number', 'Time (seconds)');
 //   data.addColumn('number', 'Memory (mb)');

    $('div:contains("- Begin Template Processing -")').each(function(){

      var replace = {"\t":'',' ':' ','&amp;nbsp;':' ','&amp;':' ','&nbsp;':' ', '-&gt;':'-'};
      var $that = $(this);

     $(this).siblings().each(function(){

          text = $(this).html();

          for (var val in replace) {
            text = text.replace(new RegExp(val, "g"), replace[val]);
          }

          if( text.indexOf( '<strong>(') == 0 ) {
              // Good - this is a log marker, clean it
            clean = text.replace( '<strong>(', '' );

            clean_arr = clean.split( ')' );
            out_text = clean_arr[1].replace('</strong>', ' ');

            clean_arr = clean_arr[0].split(' / ');
            time = clean_arr[0];
            memory = clean_arr[1];

            data.addRow( [ out_text, parseFloat(time) ] );

          }

      });

    });

    return data;
}

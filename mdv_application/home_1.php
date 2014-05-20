<?php
/**
 * Description 
 *
 * @author Niyas <niyast@live.com>
 */
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Mobile Data Visualization</title>
        <script src="<?php echo base_url(); ?>public/js/jquery.js" charset="utf-8"></script>
        <script src="<?php echo base_url(); ?>public/js/jquery-1.4.4.min.js" charset="utf-8"></script>
        <script src="<?php echo base_url(); ?>public/js/google-spreadsheet.js" charset="utf-8"></script>
        <script src="<?php echo base_url(); ?>public/js/d3.v3.min.js" charset="utf-8"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>public/js/cal-heatmap.min.js"></script>
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/css/cal-heatmap.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/css/style.css" />
    </head>
    <body>
        <div id="shadow"  >    
            <img src="<?php echo base_url() ?>public/images/loading.gif"  id="loadingImage" alt="loading..." style="display: none; margin-left: 570px; margin-top: 230px;" />
        </div>
        <div id="container" style="min-width: 1240px;">
            <a class='logout' style="float: right; padding: 10px; font-size: large;margin-top: 3px; font-weight: 400; text-decoration: none; margin-right: 10px;" href='<?php echo base_url() ?>welcome/logout'>Logout</a>
            <h1 style="text-align: center; color: #401683;" >Bamboo Mobile Health Visualization </h1>

            <div id="body">
                <h3><?php echo $text; ?> </h3>
                <div style="margin-top: 7px;margin-right: 10px; text-align: inherit;">
                    <a href="" class="fb-login" style="margin: 5px auto 3% !important;"></a>

                    <?php
                    if (isset($_SESSION['gplusuer'])) {
                        ?>
                        <br/><br/>
                        <table class="mytable">

                            <tr>
                                <td>Name:</td>
                                <td><?php print $user['name']; ?></td>
                                <td rowspan="5" valign="top"><img src="<?php print $user['picture']; ?>" width="80" style="margin-left: 15px;" /></td>
                            </tr>
                            <tr>
                                <td>Email: </td>
                                <td><span style=""><?php print $user['email']; ?></span></td>
                            </tr>
                            <tr>
                                <td>Gplus Id: </td>
                                <td><?php print $user['id']; ?></td>
                            </tr>
                            <tr>
                                <td>Gender: </td>
                                <td><?php isset($user['gender']) ? print $user['gender']  : "Not Specified"; ?></td>
                            </tr>    
                        </table>
                    <?php } ?>
                </div>
                <div style="margin-top: 20px; border-top: #999999 thin solid ;" >
                    <h2 style="color: #006666; width: 270px; " >Calender Visualization</h2>
                    <div>
                        <div class="subLinks" >
                            <a id="showPain" data-type="1" data-url="<?php echo base_url() ?>welcome/getDatacsv" onclick="getData(1)" >Pain</a>
                            <a id="" onclick="getData(2)" data-type="2" >Fatigue</a>
                            <a onclick="getData(3)" data-type="3"  >Mobility</a>
                            <a onclick="test();getData(4)"  data-type="4" >Brain Fog</a>
                            <!--                            <a >Family Stress</a>
                                                        <a >Work Stress</a>
                                                        <a >Sleep</a>
                                                        <a >Heat</a>
                                                        <a >Medication dose</a>-->
                        </div>                        
                    </div>





                    <div id="cal2" style="margin-left: -8px;" ></div>

                </div>
            </div>

            <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
        </div>

    </body>
    <script type="text/javascript">
        var calendar = new CalHeatMap();
        //                        var year = currentTime.getFullYear();
        //                        var month = currentTime.getMonth() + 1;public/samplejson1.json
        //var minimumDate = new Date();
        //minimumDate.setDate(new Date().getMonth() - 6);

        calendar.init({
            data: getData(1),
            //dataType: "json",
            itemSelector: "#cal2",
            nextSelector: "#domainDynamicDimension-next",
            previousSelector: "#domainDynamicDimension-previous",
            start: new Date(new Date().getFullYear(), (new Date().getMonth() + 1)-4),
            minDate: new Date(new Date().getFullYear(), (new Date().getMonth() + 1)-6),
            maxDate: new Date(),
            range :4,
            id : "graph_k",
            domain: "month",
            subDomain: "x_day",
            subDomainTextFormat: "%d",
            highlight: ["now"],
            subDomainTitleFormat: {
                empty : "No data on {date}",
                filled : "Severity: {count} {connector} {date}"
            },
            //subDomainDateFormat: function(date) {
            //	return moment(date).format("LL"); // Use the moment library to format the Date
            //},
            scale: [1,2,3,4,5], 
            domainGutter: 10,
            cellSize:40,
            cellpadding: 3,
            cellradius: 5,
            label: {
                position: "top"
            } ,
            displayLegend: true,
            legend: [0,1,2,3,4,5] 	// Custom threshold for the scale
        });
//        
//        $("#domainDynamicDimension-previous").on("click", function(e) {
//            e.preventDefault();
//            if (!calendar.previous()) {
//                alert("No more data to load");
//            }
//            else {
//                getData(1);
//            }
//        });
//
//        $("#domainDynamicDimension-next").on("click", function(e) {
//            e.preventDefault();
//            if (!calendar.next()) {
//                //alert("Can't go to future");
//            }
//            else {
//                getData(1);
//            }
//        });        
        
        //        $(function() {
        //            $("#showPain").click(function() {
        //            
        //            });
        //        });
        
        function getData(inpt){
            //            alert(inpt);
            $('#shadow').addClass('blocker');
            $("#loadingImage").show();
            var postUrl = $('#showPain').attr('data-url');
            var type = inpt;
            //            alert(postUrl);
            $.ajax({
                type: "POST",
                url: postUrl,
                data: {type: type},
                success: function(json) {
                    //                    var response = jQuery.parseJSON(response);
                    //                    var arr = [];
                    //                    //                    var vas = [];
                    //                    $.each(response,function(key, value){
                    //                        arr.push({
                    //                            time: value.time,
                    //                            syptom: value.symptoms
                    //                        });
                    //                        //                        vas = (value.time + ':' +  value.symptoms);
                    //                    });
                    var arr = {};
                    for(var key in json){ 
                        if(json[key].symptoms != ""){
                            var res = json[key].symptoms.split(",",inpt);
                            var ses = res[inpt-1].split(":");
                            var wew = parseInt(ses[1]);
                            arr[Math.round(json[key].time/(1000))] = wew;
                        }
                        //                        var tim = arr[json[key].time]/1000;
                        
                    }
                    console.log(arr);
                              
                    calendar.update(arr);
                    
                    $('#shadow').removeClass('blocker');
                    $("#loadingImage").hide();
                    //                    calendar.next();
                    
                    //                    var parser = function(arr) {
                    //                        var stats = {};
                    //                        for (var d in arr) {
                    //                            stats[arr[d].time] = arr[d].syptom;
                    //                        }
                    //                        return stats;
                    //                    };
                    ////                    console.log(parser);
                    //                    var calendar = new CalHeatMap();
                    //                    calendar.init({
                    //                        data: arr,
                    //                        afterLoadData: parser
                    //                    });
                    //                    $('#cal2').html(arr);

                }
            });
            
        }
        
        function test(){
            
            localStorage.clear();
            var sample_url = "https://docs.google.com/a/ms101.me/spreadsheet/pub?key=0Ast_cj5gE1aLdHJYYzRZMlk4WExPUl91YnJyN1dzelE&single=true&gid=0&output=csv";
            var url_parameter = document.location.search.split(/\?url=/)[1]
            var url = url_parameter || sample_url;
            var googleSpreadsheet = new GoogleSpreadsheet();
            googleSpreadsheet.url(url);
            googleSpreadsheet.load(function(result) {
                console.log(result);
            });
            
        }
        
    </script>
</html>
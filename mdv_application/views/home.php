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
<!--        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/css/style.css" />-->
        <link rel="shortcut icon" href="<?php echo base_url(); ?>public/images/favicon.ico" />
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="HandheldFriendly" content="true" />

        <script>
            var emailObj = '<?php echo ($user["email"]); ?>';
            var bambooID = emailObj.split("@")[0];
        </script>
    </head>
    <body>
        <div id="shadow"  >    
            <img src="<?php echo base_url() ?>public/images/loading.gif"  id="loadingImage" alt="loading..." style="display: none; margin-top: 230px;" />
        </div>
       
        <div id="title">           
            <a href="http://www.bamboomobilehealth.com" target="_blank" style="margin-left: 65px;">
                <img src="<?php echo base_url(); ?>public/images/title.PNG" />
            </a></p>
        <span id="username" class="title-white"></span>
        <a style="" class="logout" href='<?php echo base_url(); ?>'>Back</a>
        <a style="" class="logout" href='<?php echo base_url() ?>welcome/logout'>Logout</a>
    </div>

    <div id="doctorView"><br/><br/></div>	

    <div id="patientView">
        <div>
            <br/><br/>
            <span class="select" id="select" data-url="<?php echo base_url() ?>welcome/" >SELECT SYMPTOM / STRESS FACTORS : </span>
            <button class="button" id="pain" onClick="getPatientData(bambooID,1) && location.reload()">PAIN</button>
            <button class="button" id="fatigue" onClick="getPatientData(bambooID,2) && location.reload()">FATIGUE</button>
            <button class="button" id="mobility" onClick="getPatientData(bambooID,3) && location.reload()">MOBILITY</button>
            <button class="long-button" id="brainfog" onClick="getPatientData(bambooID,4) && location.reload()">BRAIN FOG</button>


                                <!-- <br/><br/><span class="select">SELECT STRESS FACTORS : </span> -->
            <button class="long-button" id="family" onClick="getEnviornmentData(bambooID,1) && location.reload()">FAMILY STRESS</button>
            <button class="long-button" id="work" onClick="getEnviornmentData(bambooID,2) && location.reload()">WORK STRESS</button>
            <button class="button" id="sleep" onClick="getEnviornmentData(bambooID,3) && location.reload()">SLEEP</button>
            <button class="long-button" id="heat" onClick="getEnviornmentData(bambooID,4) && location.reload()">HEAT/HUMIDITY</button>
            <br/><br/>
            <span class="title-white" id="caltitleSymptom"></span><span class="title-white" id="caltitleEnvironment"></span>

            <div id="cal2"></div>
            <div id="legend"><p>Severity Level Legend</div><p/>
            <div id="empty"><p>No data</p></div>
            <div id="zero"><p>0-1</p></div>
            <div id="one"><p>1-2</p></div>
            <div id="two"><p>2-3</p></div>
            <div id="three"><p>3-4</p></div>
            <div id="four"><p>4-5</p></div>
            <div id="five"><p>5+</p></div>
        </div>
    </div>	
</div>



<script type="text/javascript">
            
    var calendar = new CalHeatMap();
    //                        var year = currentTime.getFullYear();
    //                        var month = currentTime.getMonth() + 1;public/samplejson1.json
    //var minimumDate = new Date();
    //minimumDate.setDate(new Date().getMonth() - 6);
    if( screen.width <= 500) {
        calendar.init({
            data:getData(1),
            //dataType: "json",
            itemSelector: "#cal2",
            nextSelector: "#domainDynamicDimension-next",
            previousSelector: "#domainDynamicDimension-previous",
            start: new Date(new Date().getFullYear(), (new Date().getMonth() + 1)-3),
            minDate: new Date(new Date().getFullYear(), (new Date().getMonth() + 1)-6),
            maxDate: new Date(),
            range :3,
            id : "graph_c",
            domain: "month",
            subDomain: "x_day",
            subDomainTextFormat: "%a,%d",
            domainGutter: 10,
            highlight: ["now"],
            subDomainTitleFormat: {
                empty : "No data on {date}",
                filled : "Severity: {count} {connector} {date}"
            },
            onClick: function(date, nb) {
                $('#chngVal').show();
                $('#changeVal').val(nb);
                $("#onClick-placeholder").html("You just clicked <br/>on <b>" +
                    date + "</b> <br/>with value <b>" +
                    (nb === null ? "NULL" : nb) + "</b> "
            );
            },
            //subDomainDateFormat: function(date) {
            //	return moment(date).format("LL"); // Use the moment library to format the Date
            //},
            itemName: ["level", "level"],
            scale: [0,1,2,3,4,5], 
            domainGutter: 10,
            cellSize:45,
            cellPadding: 2,
            label: {
                position: "top"
            } ,
            legendCellSize: 30,
            displayLegend: false,
            legend: [0,1,2,3,4,5] 	// Custom threshold for the scale
        });
    }
    else {
        calendar.init({
            data:getData(1),
            //dataType: "json",
            itemSelector: "#cal2",
            nextSelector: "#domainDynamicDimension-next",
            previousSelector: "#domainDynamicDimension-previous",
            start: new Date(new Date().getFullYear(), (new Date().getMonth() + 1)-3),
            minDate: new Date(new Date().getFullYear(), (new Date().getMonth() + 1)-6),
            maxDate: new Date(),
            range :3,
            id : "graph_c",
            domain: "month",
            subDomain: "x_day",
            subDomainTextFormat: "%a,%d",
            domainGutter: 20,
            highlight: ["now"],
            subDomainTitleFormat: {
                empty : "No data on {date}",
                filled : "Severity: {count} {connector} {date}"
            },
            onClick: function(date, nb) {
                $('#chngVal').show();
                $('#changeVal').val(nb);
                $("#onClick-placeholder").html("You just clicked <br/>on <b>" +
                    date + "</b> <br/>with value <b>" +
                    (nb === null ? "NULL" : nb) + "</b> "
            );
            },
            //subDomainDateFormat: function(date) {
            //	return moment(date).format("LL"); // Use the moment library to format the Date
            //},
            itemName: ["level", "level"],
            scale: [0,1,2,3,4,5], 
            cellPadding: 2,
            domainGutter: 10,
            cellSize:45,
            label: {
                position: "top"
            } ,
            legendCellSize: 30,
            displayLegend: false,
            legend: [0,1,2,3,4,5] 	// Custom threshold for the scale
        });
    }
    
    $('#cancelchngval').click(function() {
        $('#changeVal').val();
        $('#chngVal').hide();
            
    });
        
    function getData(inpt){
        var type = inpt;
        $('#shadow').addClass('blocker');
        $("#loadingImage").show();
        //var emailObj = '<?php echo ($user["email"]); ?>';
        //var bambooID = emailObj.split("@")[0];
        var emailDomain = emailObj.split("@")[1];
                
        $("#username").empty().append(emailObj);
                
                
        if(emailDomain != 'ms101.me') {
            $("#select").hide();
            $("#caltitleSymptom").hide();
            $("#caltitleEnvironment").hide();
            $("#legend").hide();
            $("#patientView").hide();
            var postUrl = $('#select').attr('data-url');
            
            $.ajax({
                type: "POST",
                url: postUrl + 'getUsers',
                data: {type: type,bid:emailObj},
                //                url: "https://canary.elastic.snaplogic.com/api/1/rest/slsched/feed/snaplogic/projects/rethesh/getUsers?user_id="+emailObj,
                //                data: {type: type},
                //                beforeSend: function(xhr) { xhr.setRequestHeader("Authorization", "Basic " + btoa('rgeorge@snaplogic.com' + ":" + 'bmh@123')) },
                success: function(json) {
                    var arr = {};
                    $("#doctorView").append("<p><p><div><span class='logout' ><u>My Patients: (click to view)</u></span><p/></div>");
                    for(var key in json){
                        if(json[key] && json[key].id && json[key].id != "" && json[key].id !="0"){
                            $("#doctorView").append("</p><div><a class='logout' href='javascript:getPatientData("+json[key].id+","+inpt+");' >"+json[key].id+"</a></div>");
                        }
                    }
                    $('#shadow').removeClass('blocker');
                    $("#loadingImage").hide();							
                }
            });          	

            return null;
        }
        else {
            return getPatientData(bambooID,inpt);
        }
            
    }
 
 			
    function getPatientData(id,inpt) {

        bambooID = id;
        $('#shadow').addClass('blocker');
        $("#loadingImage").show();

        $("#doctorView").hide();
        $("#select").show();
        $("#caltitleSymptom").show();
        $("#caltitleEnvironment").hide();
        $("#legend").show();
        $("#patientView").show();
                	                
        var type = inpt;
        $("#username").empty().append(emailObj).append("<span>  Bamboo ID: </span>"+id);
        var postUrl = $('#select').attr('data-url');
        //        alert(postUrl);
        $.ajax({
            type: "POST",
            url: postUrl + 'getDatacsv',
            data: {type: type,bid:id},
            //            url: "https://canary.elastic.snaplogic.com/api/1/rest/slsched/feed/snaplogic/projects/rethesh/getData?id="+id,
            //            data: {type: type},
            //            beforeSend: function(xhr) { xhr.setRequestHeader("Authorization", "Basic " + btoa('rgeorge@snaplogic.com' + ":" + 'bmh@123')) },
            success: function(json) {

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
                    
                if(inpt == 1){
                    $('#caltitleSymptom').html('Pain Selected');
                    document.getElementById("pain").style.backgroundColor = 'yellow';
                    document.getElementById("fatigue").style.backgroundColor = 'buttonface';
                    document.getElementById("mobility").style.backgroundColor = 'buttonface';
                    document.getElementById("brainfog").style.backgroundColor = 'buttonface';
                            
                    document.getElementById("family").style.backgroundColor = 'buttonface';
                    document.getElementById("work").style.backgroundColor = 'buttonface';
                    document.getElementById("sleep").style.backgroundColor = 'buttonface';
                    document.getElementById("heat").style.backgroundColor = 'buttonface';
                                                        
                }else if(inpt == 2){
                    $('#caltitleSymptom').html('Fatigue Selected');
                    document.getElementById("fatigue").style.backgroundColor = 'yellow';
                    document.getElementById("pain").style.backgroundColor = 'buttonface';
                    document.getElementById("mobility").style.backgroundColor = 'buttonface';
                    document.getElementById("brainfog").style.backgroundColor = 'buttonface';

                    document.getElementById("family").style.backgroundColor = 'buttonface';
                    document.getElementById("work").style.backgroundColor = 'buttonface';
                    document.getElementById("sleep").style.backgroundColor = 'buttonface';
                    document.getElementById("heat").style.backgroundColor = 'buttonface';

                }else if(inpt == 3){
                    $('#caltitleSymptom').html('Mobility Selected');
                    document.getElementById("mobility").style.backgroundColor = 'yellow';
                    document.getElementById("pain").style.backgroundColor = 'buttonface';
                    document.getElementById("fatigue").style.backgroundColor = 'buttonface';
                    document.getElementById("brainfog").style.backgroundColor = 'buttonface';
 
                    document.getElementById("family").style.backgroundColor = 'buttonface';
                    document.getElementById("work").style.backgroundColor = 'buttonface';
                    document.getElementById("sleep").style.backgroundColor = 'buttonface';
                    document.getElementById("heat").style.backgroundColor = 'buttonface';

 
                }else if(inpt == 4){
                    $('#caltitleSymptom').html('Brainfog Selected');
                    document.getElementById("brainfog").style.backgroundColor = 'yellow';
                    document.getElementById("pain").style.backgroundColor = 'buttonface';
                    document.getElementById("fatigue").style.backgroundColor = 'buttonface';
                    document.getElementById("mobility").style.backgroundColor = 'buttonface';

                    document.getElementById("family").style.backgroundColor = 'buttonface';
                    document.getElementById("work").style.backgroundColor = 'buttonface';
                    document.getElementById("sleep").style.backgroundColor = 'buttonface';
                    document.getElementById("heat").style.backgroundColor = 'buttonface';


                }

                        
                $('#shadow').removeClass('blocker');
                $("#loadingImage").hide();
                        
                        
            }
        });
    }
            
    function getEnviornmentData(id,inpt) {

        bambooID = id;
        $('#shadow').addClass('blocker');
        $("#loadingImage").show();

        $("#doctorView").hide();
        $("#select").show();
        $("#caltitleEnvironment").show();
        $("#caltitleSymptom").hide();
        $("#legend").show();
        $("#patientView").show();
                	                
        var type = inpt;
        $("#username").empty().append(emailObj).append("<span>  Bamboo ID: </span>"+id);
        var postUrl = $('#select').attr('data-url');
        
        $.ajax({
            type: "POST",
            url: postUrl + 'getDataEnvrmnt',
            data: {type: type,bid:id},
            //            url: "https://canary.elastic.snaplogic.com/api/1/rest/slsched/feed/snaplogic/projects/rethesh/getEnvironmentData?id="+id,
            //            data: {type: type},
            //            beforeSend: function(xhr) { xhr.setRequestHeader("Authorization", "Basic " + btoa('rgeorge@snaplogic.com' + ":" + 'bmh@123')) },
            success: function(json) {

                var arr = {};
                for(var key in json){ 
                    if(json[key].environment != ""){
                        var res = json[key].environment.split(",",inpt);
                        var ses = res[inpt-1].split(":");
                        var wew = parseInt(ses[1]);
                        arr[Math.round(json[key].time/(1000))] = wew;
                    }
                    //                        var tim = arr[json[key].time]/1000;
                        
                }
                console.log(arr);
                              
                calendar.update(arr);
                    
                if(inpt == 1){
                    $('#caltitleEnvironment').html('Family Stress Selected');
                    document.getElementById("family").style.backgroundColor = 'yellow';
                    document.getElementById("work").style.backgroundColor = 'buttonface';
                    document.getElementById("sleep").style.backgroundColor = 'buttonface';
                    document.getElementById("heat").style.backgroundColor = 'buttonface';

                    document.getElementById("pain").style.backgroundColor = 'buttonface';
                    document.getElementById("fatigue").style.backgroundColor = 'buttonface';
                    document.getElementById("mobility").style.backgroundColor = 'buttonface';
                    document.getElementById("brainfog").style.backgroundColor = 'buttonface';


                }else if(inpt == 2){
                    $('#caltitleEnvironment').html('Work Stress Selected');
                    document.getElementById("work").style.backgroundColor = 'yellow';
                    document.getElementById("family").style.backgroundColor = 'buttonface';
                    document.getElementById("sleep").style.backgroundColor = 'buttonface';
                    document.getElementById("heat").style.backgroundColor = 'buttonface';

                    document.getElementById("pain").style.backgroundColor = 'buttonface';
                    document.getElementById("fatigue").style.backgroundColor = 'buttonface';
                    document.getElementById("mobility").style.backgroundColor = 'buttonface';
                    document.getElementById("brainfog").style.backgroundColor = 'buttonface';

                }else if(inpt == 3){
                    $('#caltitleEnvironment').html('Sleep Selected');
                    document.getElementById("sleep").style.backgroundColor = 'yellow';
                    document.getElementById("family").style.backgroundColor = 'buttonface';
                    document.getElementById("work").style.backgroundColor = 'buttonface';
                    document.getElementById("heat").style.backgroundColor = 'buttonface';

                    document.getElementById("pain").style.backgroundColor = 'buttonface';
                    document.getElementById("fatigue").style.backgroundColor = 'buttonface';
                    document.getElementById("mobility").style.backgroundColor = 'buttonface';
                    document.getElementById("brainfog").style.backgroundColor = 'buttonface';

                }else if(inpt == 4){
                    $('#caltitleEnvironment').html('Heat/Humidity Selected');
                    document.getElementById("heat").style.backgroundColor = 'yellow';
                    document.getElementById("family").style.backgroundColor = 'buttonface';
                    document.getElementById("work").style.backgroundColor = 'buttonface';
                    document.getElementById("sleep").style.backgroundColor = 'buttonface';
                        
                    document.getElementById("pain").style.backgroundColor = 'buttonface';
                    document.getElementById("fatigue").style.backgroundColor = 'buttonface';
                    document.getElementById("mobility").style.backgroundColor = 'buttonface';
                    document.getElementById("brainfog").style.backgroundColor = 'buttonface';
                        
                }
                        
                $('#shadow').removeClass('blocker');
                $("#loadingImage").hide();
                        
                        
            }
        });
    }           
                
   
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo str_replace("www.", "", $_SERVER['HTTP_HOST']).' - Leaf PHPMailer '?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.4.1/cosmo/bootstrap.min.css" rel="stylesheet" >
    <script type="text/javascript">
        window.timer = {
            internal: null,
            external: null
        };

        function email_list(count){
            var tmp = $('#emailList').val().trim();
            var lines = tmp.split("\n");
            var _ = [];
            var res = lines.splice(0, count);
            $.each(lines, function(i, line){
                _.push(line);
            });
            $.each(res, function(i, line){
                _.push(line);
            });
            $('#emailList').val(_.join("\n"));
            return res;
        }
        function smtp_list(count){
            var tmp = $('#smtpList').val().trim();
            var lines = tmp.split("\n");
            var _ = [];
            var res = lines.splice(0, count);
            $.each(lines, function(i, line){
                _.push(line);
            });
            $.each(res, function(i, line){
                _.push(line);
            });
            $('#smtpList').val(_.join("\n"));
            return res;
        }
        // function smtp_list(){
        //     var tmp = $('#smtpList').val().trim();
        //     var lines = tmp.split("\n");
        //     var _ = [];
        //     var res = lines.splice(0, 1);
        //     $.each(lines, function(i, line){
        //         _.push(line);
        //     });
        //     _.push(res[0]);
        //     $('#smtpList').val(_.join("\n"));
        //     return res[0];
        // }
        
        function email_line(email){
            var tmp = $('#emailList').val().trim();
            var lines = tmp.split("\n");
            var _ = [];
            $.each(lines, function(i, line){
                if(line!='' && line != email){
                    _.push(line);
                }
            });
            $('#emailList').val(_.join("\n"));
            return _.length;
        }

        function smtp_line(email){
            var tmp = $('#smtpList').val().trim();
            var lines = tmp.split("\n");
            var _ = [];
            $.each(lines, function(i, line){
                if(line!='' && line != email){
                    _.push(line);
                }
            });
            $('#smtpList').val(_.join("\n"));
            $('#j-good-smtp').text(_.length);
            return _.length;
        }

        function smtp_bad(email){
            var tmp = $('#badSmtp').val().trim();
            var lines = tmp.split("\n");
            var _ = [];
            $.each(lines, function(i, line){
                if(line!='' && line != email){
                    _.push(line);
                }
            });
            _.push(email);
            $('#badSmtp').val(_.join("\n"));
            $('#j-bad-smtp').text(_.length);
        }

        function stopInterval(o){
            var name = $(o).closest('h4').data('timer');
            if(window.timer[name] != null){
                clearInterval(timer[name]);
                window.timer[name]  = null;
            }
            $('.j-btn-stop').attr('disabled', 'true');
            $('.j-btn-start').removeAttr('disabled');
        }

        function my_submit(o) {
            var name = $(o).closest('h4').data('timer');
            var url = $(o).data('action');
            var post = {};
            if(name == 'external'){
                post.emails = email_list($('#multiThreads').val());
                post.smtps = smtp_list($('#multiThreads').val());
                if(post.smtp == ''){
                    $(o).next().click();
                    return;
                }
            }else{
                post.emails = email_list(1);
            }
            if(post.emails == ''){
                $(o).next().click()
                return;
            }
            //console.log(post);
            $('#j-form').ajaxSubmit({
                url: url,
                type: 'post',
                data: post,
                //'dataType: 'json',
                error: function(xhr, status, error) {
                        var err = eval("(" + xhr.responseText + ")");
                        alert(err.Message);
                },
                success: function (result) {
                    //console.log(result);
                    result = JSON.parse(result);                   
                    var data = result.data;

                    if(result.code == 200){
                        if(name == 'external'){
                            if(data.list.length == 0){
                                smtp_line(data.smtp);
                                smtp_bad(data.smtp);
                            }
                        }
                        $.each(data.list, function(i, e){
                            var count = email_line(e);
                            $('#j-emails-surplus').text(count);
                            $('.j-sendmail-count').text(parseInt($('.j-sendmail-total').text())-count);
                            if(count == 0){
                                $(o).next().click()
                            }
                        })
                    }
                }               
            });
        }
        function startInterval(o, t){
            var total = email_line();
            var name = $(o).closest('h4').data('timer');
            $('.j-sendmail-total').text(total);
            if(total > 0){
                if(window.timer[name] == null){
                    my_submit(o);
                    window.timer[name] = setInterval(function(){
                        my_submit(o);
                    }, t);
                }
                $('.j-btn').attr('disabled', 'true');
                $(o).next().removeAttr('disabled');
            }
        }
    </script>
</head>
<?php
$leaf['version']="2.8";
$leaf['website']="leafmailer.pw";
$senderEmail="support@".str_replace("www.", "", $_SERVER['HTTP_HOST']);
?>
<body>
        <form id="j-form" name="form"  method="POST" enctype="multipart/form-data" action="sendmail.php">
            <div class="container col-lg-6">
                <h3><font color="green"><span class="glyphicon glyphicon-leaf"></span></font> Leaf PHPMailer <small><?php echo $leaf['version'] ?></small></h3>
            
                <input type="hidden" name="action" value="score"/>
                <div class="row">
                    <div class="form-group col-lg-6 "><label for="senderEmail">Email</label><input type="text" class="form-control  input-sm " id="senderEmail" name="senderEmail" value="support@meilifj.com"></div>
                    <div class="form-group col-lg-6 "><label for="senderName">Sender Name</label><input type="text" class="form-control  input-sm " id="senderName" name="senderName" value="meilifujian"></div>
                </div>
                <div class="row">
                    <span class="form-group col-lg-6  "><label for="attachment">Attachment <small>(Multiple Available)</small></label><input type="file" name="attachment[]" id="attachment[]" multiple/></span>

                    <div class="form-group col-lg-6"><label for="replyTo">Reply-to</label><input type="text" class="form-control  input-sm " id="replyTo" name="replyTo" value="meilifujian@gmail.com" /></div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-12 "><label for="subject">Subject</label><input type="text" class="form-control  input-sm " id="subject" name="subject" value="Hi, [-emailuser-] Something, to write here" /></div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label for="messageLetter">Message Letter <button type="submit" class="btn btn-default btn-xs" form="form" name="action" value="view" formtarget="_blank">Preview </button></label>
                        <textarea name="messageLetter" id="messageLetter" class="form-control" rows="10">Hello, [-emailuser-] how are you? Your registration number is [-randomstring-] With the code reference is [-randommd5-]</textarea>
                    </div>
                    <div class="form-group col-lg-6 "><label for="emailList">Email List &emsp;(<span id="j-emails-surplus">0</span> lines)</label>
                        <!-- <textarea name="emailList" id="emailList" class="form-control" rows="10"></textarea> -->
                        <textarea id="emailList" class="form-control" rows="10"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-6 ">
                        <label for="messageType">Message Type</label>
                        <div class="row">
                            &emsp;&emsp;&emsp;
                            <input type="radio" name="messageType" id="messageType" value="1" checked> HTML
                            &emsp;&emsp;
                            <input type="radio" name="messageType" id="messageType" value="2">  Plain
                        </div>
                    </div>
                    <div class="form-group col-lg-3 ">
                        <label for="charset">Character set</label>
                        <select class="form-control input-sm" id="charset" name="charset">
                            <option value="UTF-8">UTF-8</option>
                            <option value="ISO-8859-1">ISO-8859-1</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-3 ">
                        <label for="encoding">Message encoding</label>
                        <select class="form-control input-sm" id="encode" name="encode">
                            <option value="8bit">8bit</option>
                            <option value="7bit">7bit</option>
                            <option value="binary">binary</option>
                            <option value="base64">base64</option>
                            <option value="quoted-printabl">quoted-printable</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="container col-lg-6"><br>
                <label for="well">Instruction &emsp;&emsp;（Status: &nbsp; <span class="j-sendmail-count" style="color: #e00;">0</span>&nbsp;emails sent &emsp;&emsp;<span class="j-sendmail-total" style="color: #000;">0</span>&nbsp;e-mails）</label>
                <div id="well" class="well well" style="padding: 10px;">
                    <h4 data-timer="internal">Shipping configuration:  &nbsp;&nbsp;&nbsp;<button type="button" class="j-btn j-btn-start btn btn-primary btn-sm" data-action="internal.php" onclick="startInterval(this, 3000)">Start</button> 
                            &emsp;
                            <button type="button" class="j-btn j-btn-stop btn btn-primary btn-sm" disabled value="inter_stop" onclick="stopInterval(this)">Stop</button> </h4>
                    <div class="row" style="border: 1px solid #ccc; margin: 8px; padding-top: 12px;">
                        <div class="form-group col-lg-12 ">
                            &emsp;&emsp;<input type="radio" name="interType" id="interType" value="inter_mail"> Mail
                            &emsp;&emsp;<input type="radio" name="interType" id="interType" value="inter_sendmail">SendMail
                            &emsp;&emsp;<input type="radio" name="interType" id="interType" value="inter_smtp" checked>SMTP
                        </div>
                    </div>
                    <h4 data-timer="external">Randomized SMTP: &emsp;&emsp; <input type="hidden" id="exter_start" name="start" value="0">
                            <button type="button" class="j-btn j-btn-start btn btn-primary btn-sm" 
                                    data-action="external.php" onclick="startInterval(this, $('#interval').val())">Start</button> 
                            &emsp;
                            <button type="button" class="j-btn j-btn-stop btn btn-primary btn-sm" disabled value="exter_stop" onclick="stopInterval(this)">Stop</button> </h4>
                    <div class="row">
                        <!-- <div class="form-group col-lg-12  " style="line-height: 32px; ">
                            <label for="attachment" class="col-sm-3 contrl-label" style="text-align: left;">SMTP List: </label>
                            <div class="col-sm-9">
                                <input type="file" name="smtpList" id="smtpList" multiple />
                            </div>
                        </div> -->
                        <div class="form-group col-lg-6 " style="line-height: 32px;">
                            <label for="interval">Interval:</label>
                            <input type="number" class="form-control  input-sm " id="interval" name="interval" value="5000" />
                        </div>
                        <div class="form-group col-lg-6 " style="line-height: 32px;">
                            <label for="multiThreads">Multi Threads:</label>
                            <input type="text" class="form-control  input-sm " id="multiThreads" name="multiThreads" value="1" /> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label for="smtpList">SMTP LIST &emsp;(<span id="j-good-smtp">0</span> lines)</label>
                            <!-- <textarea name="smtpList" id="smtpList" class="form-control" rows="10"></textarea> -->
                            <textarea id="smtpList" class="form-control" rows="10"></textarea>
                        </div>
                        <div class="form-group col-lg-6 ">
                            <label for="badSmtp">BAD SMTP &emsp;(<span id="j-bad-smtp">0</span> lines)</label>
                            <!-- <textarea name="badSmtp" id="badSmtp" class="form-control" rows="10" readonly=""></textarea> -->
                            <textarea id="badSmtp" class="form-control" rows="10" readonly=""></textarea>
                        </div>
                    </div>

                    <h4>HELP</h4>
                    <ul>
                        <li>[-email-] : <b>Reciver Email</b> (emailuser@emaildomain.com)</li>
                        <ul>
                            <li>[-emailuser-] : <b>Email User</b> (emailuser) </li>
                            <li>[-emaildomain-] : <b>Email User</b> (emaildomain.com) </li>
                        </ul>
                        <li>[-time-] : <b>Date and Time</b> ('.date("m/d/Y h:i:s a", time()).')</li>
                        
                        <li>[-randomstring-] : <b>Random string (0-9,a-z)</b></li>
                        <li>[-randomnumber-] : <b>Random number (0-9) </b></li>
                        <li>[-randomletters-] : <b>Random Letters(a-z) </b></li>
                        <li>[-randommd5-] : <b>Random MD5 </b></li>
                    </ul>
                </div>
            </div>
        </form>
    <script src="./assets/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="./assets/jquery.form.js"></script>
    <script type="text/javascript">
        $(function(){
            // $('#emailList').on('change',function(){
            //     $('#j-emails-surplus').text(email_line(''));
            // });
            // $('#j-emails-surplus').text(email_line(''));

            // $('#smtpList').on('change',function(){
            //     $('#j-good-smtp').text(smtp_line(''));
            // });
            // $('#j-good-smtp').text(smtp_line(''));
            
            //get emailList from file
            $.ajax({
                url: 'file_reader.php',
                type: 'post',
                data: {'file':'file'},
                error: function(xhr, status, error) {
                        var err = eval("(" + xhr.responseText + ")");
                        alert(err.Message);
                },
                success: function (result) {
                    result = JSON.parse(result);
                    if(result.code == 200){                        
                        arr_email = result.data.emails;
                        arr_smtp = result.data.smtps;                     
                        $('#emailList').val(arr_email.join(""));
                        $('#smtpList').val(arr_smtp.join(""));
                        $('#j-emails-surplus').text(email_line(''));
                        $('#j-good-smtp').text(smtp_line(''));
                        
                    }
                }               
            });
        });
    </script>
</body>
</html>
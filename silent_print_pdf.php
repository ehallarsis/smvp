  <html>
  <head>
  <style type="text/css">
    body {
      margin-top: 5px;
      margin-left: 5px;
      margin-right: 5px;
      margin-bottom: 5px;
    }

    textarea{
      width:100%;
    }
  </style>
  <script type="text/javascript" language="javascript">
  function showMessage(msg) {
  //  alert('showMessage called msg='+msg);
    var status_pane;
    if (window.top==window) {
      //not in another iframe or frame
      status_pane = document.getElementById("status");
      if (!status_pane) {
        var tf ='<fieldset> \n' +
                '<legend>Print status </legend> \n' +
                '<textarea id="status" rows="6" cols="96"  readonly="true"></textarea>\n' +
                '</fieldset>\n' +
                '';
        var mc = document.getElementById("msg_pane");
        if (mc) { //if exists
          //alert('msg_pane=' + mc);
          mc.innerHTML = tf;
          status_pane = document.getElementById("status");
          //alert('status pane after add tf: ' + status_pane);
        }
      }
    } else {
      //in another frame or iframe
      if (parent.parent.showMessage) {
        parent.parent.showMessage(msg);
      }
    }

    //now set message
    if (status_pane) {
      if (msg=='') {
        status_pane.innerHTML=msg;
        return;
      }
      if (document.createTextNode){
        var mytext=document.createTextNode(msg+"\r");
        status_pane.appendChild(mytext);
      } else {
        status_pane.innerHTML=msg;
      }
    }
  }

  </script>


  </head>

  <BODY link="#009900" vlink="#009900" TEXT="#4b73af">

  <?php

  //Params exteaction
  //$docList=$_GET["DOC_LIST"];
  $docList=$_SERVER["QUERY_STRING"];
  $docListener=$_GET["DOC_LISTENER"];
  $docId=$_GET["DOC_ID"];
  $printerName=$_GET["PRINTER_NAME"];
  $printerNameSubstringMatch=$_GET["PRINTER_NAME_SUBSTRING_MATCH"];
  $printerNameExcludePattern=$_GET["PRINTER_NAME_EXCLUDE_PATTERN"];
  $paper=$_GET["PAPER"];
  $copies=$_GET["COPIES"];
  $jobName=$_GET["JOB_NAME"];
  $showPrinterDialog=$_GET["SHOW_PRINT_DIALOG"];
  $autoMatchPaper=$_GET["AUTO_MATCH_PAPER"];
  $pageScaling=$_GET["PAGE_SCALING"];
  $autoRotateAndCenter=$_GET["AUTO_ROTATE_AND_CENTER"];
  $usePrinterMargins=$_GET["IS_USE_PRINTER_MARGINS"];
  $singlePrintJob=$_GET["SINGLE_PRINT_JOB"];
  $collateCopies=$_GET["COLLATE_COPIES"];
  $showErrorDialog=$_GET["SHOW_PRINT_ERROR_DIALOG"];
  $password = $_GET["PASSWORD"];
  $urlAuthId = $_GET["URL_AUTH_ID"];
  $urlAuthPassword = $_GET["URL_AUTH_PASSWORD"];
  $printQuality=$_GET["PRINT_QUALITY"];
  $side=$_GET["SIDE_TO_PRINT"];
  $statusUpdateEnabled=$_GET["STATUS_UPDATE_ENABLED"];
  $debug=$_GET["DEBUG"];

  $successPage=$_GET["ON_SUCCESS_SHOW_PAGE"];
  $successPageTarget=$_GET["ON_SUCCESS_PAGE_TARGET"];
  $failurePage=$_GET["ON_FAILURE_SHOW_PAGE"];
  $failurePageTarget=$_GET["ON_FAILURE_PAGE_TARGET"];
  $serverCallBackUrl=$_GET["SERVER_CALL_BACK_URL"];
  $isShowPrintPreview=$_GET["IS_SHOW_PRINT_PREVIEW"];
  $viewerPage=$_GET["VIEWER_PAGE"];
  $viewerControls=$_GET["VIEWER_CONTROLS"];
  $zoomComboValues=$_GET["ZOOM_COMBO_VALUES"];
  $debugLevel=$_GET["DEBUG_LEVEL"];


  ///Test the params////
  //echo "docList=${docList}<br>\r\n";
  //echo "printerName=${printerName}<br>\r\n";
  //echo "printerNameSubstringMatch=${printerNameSubstringMatch}<br>\r\n";
  //echo "papers=${paper}<br>\r\n";
  //echo "copies=${copies}<br>\r\n";
  //echo "jobName=${jobName}<br>\r\n";
  //echo "showPrinterDialog=${showPrinterDialog}<br>\r\n";
  //echo "autoMatchPaper=${autoMatchPaper}<br>\r\n";
  //echo "pageScaling=${pageScaling}<br>\r\n";
  //echo "autoRotateAndCenter=${autoRotateAndCenter}<br>\r\n";
  //echo "usePrinterMargins=${usePrinterMargins}<br>\r\n";
  //echo "singlePrintJob=${singlePrintJob}<br>\r\n";
  //echo "collateCopies=${collateCopies}<br>\r\n";
  //echo "showErrorDialog=${showErrorDialog}<br>\r\n";
  //echo "password=${password}<br>\r\n";
  //echo "printQuality=${printQuality}<br>\r\n";
  //echo "side=${side}<br>\r\n";
  //echo "statusUpdateEnabled=${statusUpdateEnabled}<br>\r\n";
  //echo "debug=${debug}<br>\r\n";
  ?>


  <OBJECT
    width="2" height="2"
     classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93"
    codebase="http://java.sun.com/update/1.6.0/jinstall-6-windows-i586.cab#Version=1,6,0,16"
    >
    <param name="java_arguments" value="-Xmx412m">
    <param name="java_version" value="1.6+">
    <PARAM NAME="name" VALUE="SilentPrint">
    <PARAM NAME="alt" VALUE="Silent Print PDF">
    <PARAM NAME="CODEBASE" VALUE="browser_lib/">
    <PARAM NAME="CODE" VALUE="demo.activetree.pdfprint.browser.MySilentPrintFromBrowser">
    <PARAM NAME="ARCHIVE" VALUE="">
    <PARAM NAME="cache_option" VALUE="Plugin">
    <PARAM name="codebase_lookup" value="false">
    <PARAM NAME="cache_archive" VALUE="bc.jar,jai_codec.jar,jai_core.jar,smartjcommon.jar,smartjprint.jar,smartjprint_demo.jar">
    <PARAM NAME="DOC_LIST" VALUE=<?php echo "\"${docList}\""; ?>>
    <PARAM NAME="DOC_LISTENER" VALUE=<?php echo "\"${docListener}\""; ?>>
    <PARAM NAME="DOC_ID" VALUE=<?php echo "\"${docId}\""; ?>>
    <PARAM NAME="PAGE_SCALING" VALUE=<?php echo "\"${pageScaling}\""; ?>>
    <PARAM NAME="AUTO_ROTATE_AND_CENTER" VALUE=<?php echo "\"${autoRotateAndCenter}\""; ?>>
    <PARAM NAME="AUTO_MATCH_PAPER" VALUE=<?php echo "\"${autoMatchPaper}\""; ?>>
    <PARAM NAME="IS_USE_PRINTER_MARGINS" VALUE=<?php echo "\"${usePrinterMargins}\""; ?>>
    <PARAM NAME="PRINTER_NAME" VALUE=<?php echo "\"${printerName}\""; ?>>
    <PARAM NAME="PRINTER_NAME_SUBSTRING_MATCH" VALUE=<?php echo "\"${printerNameSubstringMatch}\""; ?>>
    <PARAM NAME="PRINTER_NAME_EXCLUDE_PATTERN" VALUE=<?php echo "\"${printerNameExcludePattern}\""; ?>>
    <PARAM NAME="PAPER" VALUE=<?php echo "\"${paper}\""; ?>>
    <PARAM NAME="COPIES" VALUE=<?php echo "\"${copies}\""; ?>>
    <PARAM NAME="COLLATE_COPIES" VALUE=<?php echo "\"${collateCopies}\""; ?>>
    <PARAM NAME="JOB_NAME" VALUE=<?php echo "\"${jobName}\""; ?>>
    <PARAM NAME="SHOW_PRINT_DIALOG" VALUE=<?php echo "\"${showPrinterDialog}\""; ?>>
    <PARAM NAME="SINGLE_PRINT_JOB" VALUE=<?php echo "\"${singlePrintJob}\""; ?>>
    <PARAM NAME="SHOW_PRINT_ERROR_DIALOG" VALUE=<?php echo "\"${showErrorDialog}\""; ?>>
    <PARAM NAME="PASSWORD" VALUE=<?php echo "\"${password}\""; ?>>
    <PARAM NAME="PRINT_QUALITY" VALUE=<?php echo "\"${printQuality}\""; ?>>
    <PARAM NAME="SIDE_TO_PRINT" VALUE=<?php echo "\"${side}\""; ?>>
    <PARAM NAME="STATUS_UPDATE_ENABLED" VALUE=<?php echo "\"${statusUpdateEnabled}\""; ?>>
    <PARAM NAME="ON_SUCCESS_SHOW_PAGE" VALUE=<?php echo "\"${successPage}\""; ?>>
    <PARAM NAME="ON_SUCCESS_PAGE_TARGET" VALUE=<?php echo "\"${successPageTarget}\""; ?>>
    <PARAM NAME="ON_FAILURE_SHOW_PAGE" VALUE=<?php echo "\"${failurePage}\""; ?>>
    <PARAM NAME="ON_FAILURE_PAGE_TARGET" VALUE=<?php echo "\"${failurePageTarget}\""; ?>>
    <PARAM NAME="SERVER_CALL_BACK_URL" VALUE=<?php echo "\"${serverCallBackUrl}\""; ?>>
    <PARAM NAME="IS_SHOW_PRINT_PREVIEW" VALUE=<?php echo "\"${isShowPrintPreview}\""; ?>>
    <PARAM NAME="VIEWER_PAGE" VALUE=<?php echo "\"${viewerPage}\""; ?>>
    <PARAM NAME="VIEWER_CONTROLS" VALUE=<?php echo "\"${viewerControls}\""; ?>>
    <PARAM NAME="ZOOM_COMBO_VALUES" VALUE=<?php echo "\"${zoomComboValues}\""; ?>>
    <PARAM NAME="DEBUG_LEVEL" VALUE=<?php echo "\"${debugLevel}\""; ?>>
    <PARAM NAME="DEBUG" VALUE=<?php echo "\"${debug}\""; ?>>

    <COMMENT>
      <EMBED
        type="application/x-java-applet"
        pluginspage="http://java.sun.com/j2se/"
        java_arguments="-Xmx412m"
        java_version="1.6+"
        name="SilentPrint"
        alt="Silent Print PDF"
        CODEBASE="browser_lib/"
        CODE="demo.activetree.pdfprint.browser.MySilentPrintFromBrowser"
        ARCHIVE=""
        cache_option="Plugin"
        codebase_lookup="false"
        cache_archive="bc.jar,jai_codec.jar,jai_core.jar,smartjcommon.jar,smartjprint.jar,smartjprint_demo.jar"
        WIDTH="2"
        HEIGHT="2"
        DOC_LIST=<?php echo "\"${docList}\"\r\n"; ?>
        DOC_LISTENER=<?php echo "\"${docListener}\"\r\n"; ?>
        DOC_ID=<?php echo "\"${docId}\"\r\n"; ?>
        PAGE_SCALING=<?php echo "\"${pageScaling}\"\r\n"; ?>
        AUTO_ROTATE_AND_CENTER=<?php echo "\"${autoRotateAndCenter}\"\r\n"; ?>
        AUTO_MATCH_PAPER=<?php echo "\"${autoMatchPaper}\"\r\n"; ?>
        IS_USE_PRINTER_MARGINS=<?php echo "\"${usePrinterMargins}\"\r\n"; ?>
        PRINTER_NAME=<?php echo "\"${printerName}\"\r\n"; ?>
        PRINTER_NAME_SUBSTRING_MATCH=<?php echo "\"${printerNameSubstringMatch}\"\r\n"; ?>
        PRINTER_NAME_EXCLUDE_PATTERN=<?php echo "\"${printerNameExcludePattern}\"\r\n"; ?>
        PAPER=<?php echo "\"${paper}\"\r\n"; ?>
        COPIES=<?php echo "\"${copies}\"\r\n"; ?>
        COLLATE_COPIES=<?php echo "\"${collateCopies}\"\r\n"; ?>
        JOB_NAME=<?php echo "\"${jobName}\"\r\n"; ?>
        SHOW_PRINT_DIALOG=<?php echo "\"${showPrinterDialog}\"\r\n"; ?>
        SINGLE_PRINT_JOB=<?php echo "\"${singlePrintJob}\"\r\n"; ?>
        SHOW_PRINT_ERROR_DIALOG=<?php echo "\"${showErrorDialog}\"\r\n"; ?>
        PASSWORD=<?php echo "\"${password}\"\r\n"; ?>
        PRINT_QUALITY=<?php echo "\"${printQuality}\""; ?>
        SIDE_TO_PRINT=<?php echo "\"${side}\""; ?>
        STATUS_UPDATE_ENABLED=<?php echo "\"${statusUpdateEnabled}\""; ?>
        ON_SUCCESS_SHOW_PAGE=<?php echo "\"${successPage}\""; ?>
        ON_SUCCESS_PAGE_TARGET=<?php echo "\"${successPageTarget}\""; ?>
        ON_FAILURE_SHOW_PAGE=<?php echo "\"${failurePage}\""; ?>
        ON_FAILURE_PAGE_TARGET=<?php echo "\"${failurePageTarget}\""; ?>
        SERVER_CALL_BACK_URL=<?php echo "\"${serverCallBackUrl}\""; ?>
        IS_SHOW_PRINT_PREVIEW=<?php echo "\"${isShowPrintPreview}\""; ?>
        VIEWER_PAGE=<?php echo "\"${viewerPage}\""; ?>
        VIEWER_CONTROLS=<?php echo "\"${viewerControls}\""; ?>
        ZOOM_COMBO_VALUES=<?php echo "\"${zoomComboValues}\""; ?>
        DEBUG_LEVEL=<?php echo "\"${debugLevel}\""; ?>
        DEBUG=<?php echo "\"${debug}\""; ?>
        >

        <NOEMBED>
          <p>No java runtime</p>
        </NOEMBED>
      </EMBED>
      </COMMENT>
  </OBJECT>

  <!--This section is for message display on browser page -->
  <table border="0" width="100%" cellpadding="0" cellspacing="0">
    <tr width="100%">
      <td width="100%" >
        <div id="msg_pane" align="center">
        </div>
      </td>
    </tr>
  </table>
  <!-- end message display -->

  </body>
  </html>

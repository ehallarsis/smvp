function printWithParams(params) {
  var output="<IFRAME src='"+params+"' id='print_message_frame' name='print_message_frame' marginwidth='0' marginheight='0' width='80%' height='0' hspace='0' vspace='0' frameborder='0' halign='left' valign='top' scrolling='no'> </IFRAME>";
  var print_pane = document.getElementById("print_pane");
  if (print_pane) {
    print_pane.innerHTML=output;
  }
}
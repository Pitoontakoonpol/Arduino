
function save_settings() {
    var Invoice_Title = $("#Invoice_Title").val();
    var Address_Title = $("#Address_Title").val();
    var Set_System_View = $("#Set_System_View").val();
    var Set_VAT = $("#Set_VAT").val();
    var Set_LandingIP = $("#Set_LandingIP").val();
    var Set_Brief_Report = $("#Set_Brief_Report").val();
    var Set_Screen2 = $("#Set_Screen2").val();
    var Set_Service_Charge = $("#Set_Service_Charge").val();
    var TAXID = $("#TAXID").val();
    var POSID = $("#POSID").val();
    var Queue = $("#Queue").is(':checked');
    var Member_Name = $("#Member_Name").is(':checked');
    var Member_Point = $("#Member_Point").is(':checked');
    var Footer = $("#Footer").val();
    var Footer_Option = $("#Footer_Option").val();
    var Lang_POS = $("#Lang_POS").val();
    var Lang_Bill = $("#Lang_Bill").val();
    var Currency = $("#Currency").val();
    var Ticket = $("#Billing_Ticket").val();

    var Member_Register_Point = $("#Member_Register_Point").val();
    var Delivery_Min_Item = $("#Delivery_Min_Item").val();
    var Delivery_Min_Baht = $("#Delivery_Min_Baht").val();

    var payment_option = '';
    $(".Payment-Option:checked").each(function () {
        var optID = $(this).attr("id").substr(8, 3);
        payment_option += optID + ',';
    });


    var additional = "&Invoice_Title=" + Invoice_Title;
    additional += "&Address_Title=" + Address_Title;
    additional += "&Set_System_View=" + Set_System_View;
    additional += "&Set_VAT=" + Set_VAT;
    additional += "&Set_Brief_Report=" + Set_Brief_Report;
    additional += "&Set_Screen2=" + Set_Screen2;
    additional += "&Set_LandingIP=" + Set_LandingIP;
    additional += "&Set_Service_Charge=" + Set_Service_Charge;
    additional += "&TAXID=" + TAXID;
    additional += "&POSID=" + POSID;
    additional += "&Queue=" + Queue;
    additional += "&Member_Name=" + Member_Name;
    additional += "&Member_Point=" + Member_Point;
    additional += "&Footer=" + Footer;
    additional += "&Footer_Option=" + Footer_Option;
    additional += "&Lang_POS=" + Lang_POS;
    additional += "&Lang_Bill=" + Lang_Bill;
    additional += "&Currency=" + Currency;
    additional += "&Payment_Option=" + payment_option;
    additional += "&Member_Register_Point=" + Member_Register_Point;
    additional += "&Delivery_Min_Item=" + Delivery_Min_Item;
    additional += "&Delivery_Min_Baht=" + Delivery_Min_Baht;
    additional += "&Ticket=" + Ticket;
    additional = additional.replace(/\n/g, "%0a").replace(/ /g, "%20").replace(/undefined/g, "");
    $("#spare").load("operation.php?opr=save_setting&br=" + br + additional, function (data) {
        amsalert('Save Changes', 'white', 'green');

        localStorage.Set_Bill_Title = Invoice_Title.replace(/ /g, "%20");
        localStorage.Set_Address_Title = Address_Title.replace(/\n/g, "%0a").replace(/ /g, "%20");
        localStorage.Set_System_View = Set_System_View;
        localStorage.Set_TAXID = TAXID.replace(/ /g, "%20");
        localStorage.Set_POSID = POSID.replace(/ /g, "%20");
        localStorage.Set_Lang_POS = Lang_POS;
        localStorage.Set_Lang_Bill = Lang_Bill;
        localStorage.Set_Currency = Currency;
        localStorage.Set_Footer_Option = Footer_Option;
        localStorage.Set_Brief_Report = Set_Brief_Report;
        localStorage.Set_Screen2 = Set_Screen2;
        localStorage.Set_Payment_Option = payment_option;
        if (Queue === true) {
            localStorage.Set_Queue = '1';
        } else {
            localStorage.Set_Queue = '0';
        }

        if (Member_Name === true) {
            localStorage.Set_Member_Name = '1';
        } else {
            localStorage.Set_Member_Name = '0';
        }
        if (Member_Point === true) {
            localStorage.Set_Member_Point = '1';
        } else {
            localStorage.Set_Member_Point = '0';
        }

        localStorage.Set_Footer = Footer.replace(/\n/g, "%0a").replace(/ /g, "%20");
    });

}
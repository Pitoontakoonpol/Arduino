<?php
include("../setting/db_username_permission.php");
?>
<script type="text/javascript">
    function checkbox_checking (
    Appointment,
    Customer,
    Vehicle,
    Database,
    Point_Option,
    Front_Guard,
    Statistic,
    Step1,
    Step2,
    Step4,
    Step6,
    Step8,
    Step10,
    Step11,
    Step13,
    Sales_Manager,
    Service_Manager,
    Point_Manager){
        if (Appointment.charAt(0)==0) $("#primary-Appointment").hide();
        if (Appointment.charAt(1)==0) $(".appointment-button-add").hide();
        if (Appointment.charAt(2)==0) $(".veh-list").attr("onclick","");
        
        if (Customer.charAt(0)==0) $("#primary-Customer").hide();
        if (Customer.charAt(1)==0) $(".customer-button-add").hide();
        if (Customer.charAt(2)==0) $(".customer-button-edit").hide();
        if (Customer.charAt(3)==0) $(".customer-button-delete").hide();
        
        if (Vehicle.charAt(0)==0) $("#primary-Vehicle").hide();
        if (Vehicle.charAt(1)==0) $(".vehicle-button-add").hide();
        if (Vehicle.charAt(2)==0) $(".vehicle-button-edit").hide();
        if (Vehicle.charAt(3)==0) $(".vehicle-button-delete").hide();
                
        if (Database.charAt(0)==0) $("#primary-Database").hide();
        if (Database.charAt(1)==0) $(".database-button-add").hide();
        
        if (Point_Option.charAt(0)==0) $("#primary-Point_Option").hide();
        if (Point_Option.charAt(1)==0) $(".point_option-button-add").hide();
        if (Point_Option.charAt(2)==0) $(".point_option-button-edit").hide();
        if (Point_Option.charAt(3)==0) $(".point_option-button-delete").hide();
        
        if (Front_Guard.charAt(0)==0) $("#primary-Front_Guard").hide();
        if (Statistic.charAt(0)==0) $("#primary-Statistic").hide();
        
        
        if (Sales_Manager.charAt(0)==0) $("#sales-manager").hide();
        if (Service_Manager.charAt(0)==0) $(".service-special").hide();
        if (Point_Manager.charAt(0)==0) $(".point-special").hide();
        
        
        var Status_Type=$("select#Status_Type").val();
        
        if (Step1.charAt(0)==0) $(".step1").hide();
        if (Step2.charAt(0)==0) $(".step2").hide();
        if (Step4.charAt(0)==0) $(".step4").hide();
        if (Step6.charAt(0)==0) $(".step6").hide();
        if (Step8.charAt(0)==0) $(".step8").hide();
        if (Step10.charAt(0)==0) $(".step10").hide();
        if (Step11.charAt(0)==0) $(".step11").hide();
        if (Step13.charAt(0)==0) $(".step13").hide();
        
        
        if (Status_Type=='Guard In') { 
            if (Step1.charAt(1)==0) $(".navigation-left").hide();
            if (Step1.charAt(2)==0) $(".navigation-right").hide();
        }
        
        if (Status_Type=='Pre Check') { 
            if (Step2.charAt(1)==0) $(".navigation-left").hide();
            if (Step2.charAt(2)==0) $(".navigation-right").hide();
        }
        
        if (Status_Type=='Job') { 
            if (Step4.charAt(1)==0) $(".navigation-left").hide();
            if (Step4.charAt(2)==0) $(".navigation-right").hide();
        }
        
        if (Status_Type=='Service') { 
            if (Step6.charAt(1)==0) $(".navigation-left").hide();
            if (Step6.charAt(2)==0) $(".navigation-right").hide();
        }
        
        if (Status_Type=='Wash') { 
            if (Step8.charAt(1)==0) $(".navigation-left").hide();
            if (Step8.charAt(2)==0) $(".navigation-right").hide();
        }
        
        if (Status_Type=='Deliver') { 
            if (Step10.charAt(1)==0) $(".navigation-left").hide();
            if (Step10.charAt(2)==0) $(".navigation-right").hide();
        }
        
        if (Status_Type=='Payment') { 
            if (Step11.charAt(1)==0) $(".navigation-left").hide();
            if (Step11.charAt(2)==0) $(".navigation-right").hide();
        }
        
        if (Status_Type=='Guard Out') { 
            if (Step13.charAt(1)==0) $(".navigation-left").hide();
            if (Step13.charAt(2)==0) $(".navigation-right").hide();
        }
    }
    
    checkbox_checking(
    '<?= $Permission_Appointment ?>',
    '<?= $Permission_Customer ?>',
    '<?= $Permission_Vehicle ?>',
    '<?= $Permission_Database ?>',
    '<?= $Permission_Point_Option ?>',
    '<?= $Permission_Front_Guard ?>',
    '<?= $Permission_Statistic ?>',
    '<?= $Permission_Step1 ?>',
    '<?= $Permission_Step2 ?>',
    '<?= $Permission_Step4 ?>',
    '<?= $Permission_Step6 ?>',
    '<?= $Permission_Step8 ?>',
    '<?= $Permission_Step10 ?>',
    '<?= $Permission_Step11 ?>',
    '<?= $Permission_Step13 ?>',
    '<?= $Permission_Sales_Manager ?>',
    '<?= $Permission_Service_Manager ?>',
    '<?= $Permission_Point_Manager ?>');
</script>
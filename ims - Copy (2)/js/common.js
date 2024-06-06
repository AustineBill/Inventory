$(document).ready(function() {
    var url = window.location.pathname.split("/").pop();
    var page = url.substr(0, url.lastIndexOf('.'));
    $("a#" + page + "_menu").css({ 'color': '#FFF' });

    var inventoryData = $('#inventoryDetails').DataTable({
        "processing": true,
        "serverSide": true,
        "lengthChange": false,
        "order": [],
        "ajax": {
            url: "action.php",
            type: "POST",
            data: { action: 'getInventoryDetails' },
            dataType: "json",
        },
        'rowCallback': function(row, data, index) {
            $(row).find('td').addClass('align-middle text-end')
            $(row).find('td:eq(0)').removeClass('text-end').addClass('text-center')
            $(row).find('td:eq(1)').removeClass('text-end')
            if (data[6] < 0) {
                $(row).find('td:eq(6)').css({ 'color': 'red', 'font-weight': 'bold' });
            } else {
                $(row).find('td:eq(6)').css({ 'color': 'green', 'font-weight': 'bold' });
            }

            var inventoryInHand = parseFloat(data[6]);
	            if (inventoryInHand < 0) {
	                $(row).find('td:eq(6)').css({ 'color': 'red', 'font-weight': 'bold' });
	            } else {
	                $(row).find('td:eq(6)').css({ 'color': 'green', 'font-weight': 'bold' });
	            }

	            if (inventoryInHand <= 15) {
	                alert('Warning: Inventory In Hand for ' + data[1] + ' is ' + inventoryInHand + '  ' +'Please Re-Purchase') ;
	            }
        },
        "pageLength": 10
    });

});
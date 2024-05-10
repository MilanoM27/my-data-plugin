jQuery(document).ready(function ($) {
    // Function to filter data based on selected criteria
    function filterData() {
        var type_of_accounts = [];
        var type_of_assets = [];

        // Get selected account types
        $('input[name="type_of_account"]:checked').each(function () {
            type_of_accounts.push($(this).val());
        });

        // Get selected asset types
        $('input[name="type_of_asset"]:checked').each(function () {
            type_of_assets.push($(this).val());
        });

        // AJAX request to filter data
        $.ajax({
            url: custom_plugin_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'custom_plugin_filter_data',
                type_of_accounts: type_of_accounts,
                type_of_assets: type_of_assets
            },
            success: function (response) {
                // Update table with filtered data
                updateTable(response);
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
    }

    // Function to update table with filtered data
    function updateTable(data) {
        var tableBody = $('#custom-plugin-table tbody');
        tableBody.empty(); // Clear existing data

        // Check if data is empty
        if (data.length === 0) {
            // If no data is available, display a message or handle it as needed
            tableBody.append('<tr><td colspan="2">No data available</td></tr>');
        } else {
            // Append filtered data to table
            $.each(data, function (index, item) {
                var row = $('<tr>');
                row.append('<td>' + item.account_type + '</td>');
                row.append('<td>' + item.asset_type + '</td>');
                // Add more columns as needed
                tableBody.append(row);
            });
        }
    }

    // Event listener for filter button click
    $('#filter-btn').click(function () {
        filterData();
    });

    // Initial data load on page load
    filterData();
});

jQuery(document).ready(function ($) {
    const ctx = document.getElementById('ecm-consent-chart').getContext('2d');

    // Initialize chart with placeholder data.
    const consentChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Accepted', 'Rejected'],
            datasets: [
                {
                    label: 'Consent Summary',
                    data: [0, 0], // Placeholder data
                    backgroundColor: ['#4caf50', '#f44336'],
                },
            ],
        },
    });

    // Fetch data for the chart.
    function fetchConsentSummary() {
        $.ajax({
            url: ecm_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'ecm_get_consent_summary',
            },
            success: function (response) {
                if (response.success) {
                    // Update chart data.
                    consentChart.data.datasets[0].data = [
                        response.data.accepted,
                        response.data.rejected,
                    ];
                    consentChart.update();
                } else {
                    console.error('Error fetching consent summary:', response.data.message);
                }
            },
        });
    }

    // Fetch data on page load.
    fetchConsentSummary();
});

jQuery(document).ready(function ($) {
    // Fetch real-time stats.
    function fetchStats() {
        $.ajax({
            url: ecm_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'ecm_get_stats',
            },
            success: function (response) {
                if (response.success) {
                    $('#ecm-total-consents').text(response.data.total_consents);
                    $('#ecm-total-cookies').text(response.data.total_cookies);
                }
            },
        });
    }

    // Trigger stats refresh every 10 seconds.
    setInterval(fetchStats, 10000);

    // Handle "Run Cookie Scan" button.
    $('#ecm-run-scan').on('click', function (e) {
        e.preventDefault();
        alert('Cookie scan triggered! (Placeholder for real scan logic)');
    });
});

jQuery(document).ready(function ($) {
    // Consent summary chart.
    const ctx = document.getElementById('ecm-consent-chart').getContext('2d');
    const consentChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Accepted', 'Rejected'],
            datasets: [
                {
                    label: 'Consent Summary',
                    data: [60, 40], // Placeholder data; replace with real data.
                    backgroundColor: ['#4caf50', '#f44336'],
                },
            ],
        },
    });
});

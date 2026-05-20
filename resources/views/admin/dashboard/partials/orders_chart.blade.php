<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Volume de Pedidos por Mês</h3>
    </div>
    <div class="box-body">
        <div style="position:relative; height:300px;">
            <canvas id="ordersChart"></canvas>
        </div>
    </div>
</div>

<script>
    function initOrdersChart() {
        var canvas = document.getElementById('ordersChart');
        if (!canvas) return;
        
        var datos = {!! json_encode($ordersPerMonth->toArray()) !!};
        
        var labels = [];
        var chartData = [];
        var meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        
        if (datos && datos.length > 0) {
            datos.forEach(function(item) {
                var mesIndex = parseInt(item.mes) - 1;
                var mesNome = meses[mesIndex];
                var label = mesNome + ' ' + item.ano;
                labels.push(label);
                chartData.push(parseInt(item.total));
            });
        }

        if (chartData.length === 0) {
            canvas.parentElement.innerHTML = '<p style="text-align:center; padding-top:50px;">Sem dados disponíveis</p>';
            return;
        }

        var ctx = canvas.getContext('2d');
        if (ctx && typeof Chart !== 'undefined') {
            window.myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Número de Pedidos',
                        data: chartData,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                responsive: true,
                maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                min: 0,
                                stepSize: 1
                            }
                        }]
                    }
                }
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(initOrdersChart, 500);
        });
    } else {
        setTimeout(initOrdersChart, 500);
    }
</script>

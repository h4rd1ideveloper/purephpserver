<?php
/***
 * Scripts to include dynamic
 **/
?>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.9"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

<script type="text/javascript">
    $('document').ready(function () {
        //console.log(isToday(new Date(2014, 9, 6, 14, 0)));
        labels = JSON.parse(`<?php  if (isset($chartLabels)) {
            echo $chartLabels;
        } else {
            echo '[]';
        };?>`);
        dateAndCountFinds = JSON.parse(`<?php  if (isset($dateAndCountFinds)) {
            echo json_encode((array)$dateAndCountFinds, JSON_UNESCAPED_UNICODE);
        } else {
            echo '[]';
        };?>`);
        dateAndCountNotFinds = JSON.parse(`<?php  if (isset($dateAndCountNotFinds)) {
            echo json_encode((array)$dateAndCountNotFinds, JSON_UNESCAPED_UNICODE);
        } else {
            echo '[]';
        };?>`);
        const datafind = labels.map(date => {
            return dateAndCountFinds[date] || 0
        });
        const dataNotFind = labels.map(date => {
            return dateAndCountNotFinds[date] || 0
        });
        console.log({dataNotFind,datafind,dateAndCountNotFinds, dateAndCountFinds, labels});
        if (document.getElementById('myChart') && labels.length > 1) {
            var ctx = document.getElementById('myChart').getContext('2d');
            var chart = new Chart(ctx, {
                // The type of chart we want to create
                type: 'line',

                // The data for our dataset
                data: {
                    labels: [...labels],
                    datasets: [
                        {
                            label: 'Encontradas',
                            borderColor: 'rgb(220, 120, 132)',
                            data: [...datafind]
                        },
                        {
                            label: 'Não Encontradas',
                            borderColor: 'rgb(120, 99, 132)',
                            data: [...dataNotFind]
                        }
                    ]
                },

                // Configuration options go here
                options: {
                    responsive:true,
                    responsiveAnimationDuration:800,
                    maintainAspectRatio:true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        }
    });
</script>
import React, { useEffect, useState } from 'react'

export default function ChartComponent({ finds, notFind }) {
    const [{ isOk }, setState] = useState({ isOk: false })

    useEffect(function () {
        if (finds.length || notFind.length) {
            merged = [...find, ...notFind]
            labels = merged.map(obj => someDate(obj))
            
            setState({ isOk: true })
            new Chart(document.getElementById('myChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: [...labels],
                    datasets: [{
                        label: 'Encontradas',
                        borderColor: 'rgb(220, 120, 132)',
                        data: [...datafind]
                    },
                    {
                        label: 'Não Encontradas',
                        borderColor: 'rgb(120, 99, 132)',
                        data: [...dataNotFind]
                    }]
                },
                options: {
                    responsive: true,
                    responsiveAnimationDuration: 800,
                    maintainAspectRatio: true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            })
        }
    }, [finds, notFind])

    return (
        <Row className={`${!isOk && 'd-none'}`}>
            <Col>
                <canvas id="myChart"></canvas>
            </Col>
        </Row>
    );


}
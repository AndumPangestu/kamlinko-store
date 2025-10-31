@props(['canvasId', 'chartData', 'label', 'type' => 'line', 'xTitle' => 'Date', 'yTitle' => 'Total', 'height' => 'height'])

<div id="canvas">
    <canvas id="{{ $canvasId }}"></canvas>
</div>
@push('scripts')

<style>
    #canvas{
        height: 15rem;
    }
</style>
<script>
        
        document.addEventListener('DOMContentLoaded', function() {
            const data = @json($chartData);
            const labels = data.map(item => item.date);
            const values = data.map(item => item.total);

            const ctx = document.getElementById('{{ $canvasId }}').getContext('2d');

            new Chart(ctx, {
                type: '{{ $type }}',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '{{ $label }}',
                        data: values,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        fill: false,
                    }]
                },
                options: {
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: '{{ $xTitle }}'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: '{{ $yTitle }}'
                            },
                            beginAtZero: true
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });
    </script>
@endpush

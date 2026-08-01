@extends('layouts.app')

@section('title', 'Dasbor - Inventaris Dewi Catering')

@section('content')
<div class="row g-3 mb-4">
    <!-- Total Produk -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-primary text-white p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-1 small">Total Produk</h6>
                    <h3 class="mb-0 fw-bold">{{ $totalProduk }}</h3>
                </div>
                <i class="fa-solid fa-boxes-stacked fa-2x opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Total Supplier -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-success text-white p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-1 small">Total Supplier</h6>
                    <h3 class="mb-0 fw-bold">{{ $totalSupplier }}</h3>
                </div>
                <i class="fa-solid fa-truck fa-2x opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Total Pesanan -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-warning text-dark p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-1 small">Total Pesanan PO</h6>
                    <h3 class="mb-0 fw-bold">{{ $totalPesanan }}</h3>
                </div>
                <i class="fa-solid fa-cart-shopping fa-2x opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Total Pengeluaran -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-info text-white p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-1 small">Total Belanja (Selesai)</h6>
                    <h4 class="mb-0 fw-bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h4>
                </div>
                <i class="fa-solid fa-wallet fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Highcharts (Data disisipkan via Data Attributes) -->
<div class="row g-3" 
     id="chart-data-holder"
     data-pie='@json($pieChartData)'
     data-categories='@json($supplierNames)'
     data-series='@json($supplierPoCounts)'>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm p-3">
            <div id="statusChart"></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm p-3">
            <div id="supplierChart"></div>
        </div>
    </div>
</div>

<!-- Highcharts Script -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Ambil data aman dari elemen HTML Data Attributes
        const dataHolder = document.getElementById('chart-data-holder');
        const pieData = JSON.parse(dataHolder.getAttribute('data-pie'));
        const categoriesData = JSON.parse(dataHolder.getAttribute('data-categories'));
        const seriesData = JSON.parse(dataHolder.getAttribute('data-series'));

        // Pie Chart Status Pesanan
        Highcharts.chart('statusChart', {
            chart: { type: 'pie' },
            title: { text: 'Proporsi Status Pesanan Pembelian' },
            tooltip: { pointFormat: '{series.name}: <b>{point.y} Order</b>' },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: { enabled: true, format: '<b>{point.name}</b>: {point.y}' }
                }
            },
            series: [{
                name: 'Jumlah',
                colorByPoint: true,
                data: pieData
            }]
        });

        // Bar Chart Top Supplier
        Highcharts.chart('supplierChart', {
            chart: { type: 'column' },
            title: { text: 'Top 5 Supplier Berdasarkan Transaksi' },
            xAxis: {
                categories: categoriesData,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: { text: 'Jumlah Transaksi (PO)' }
            },
            series: [{
                name: 'Jumlah PO',
                data: seriesData,
                color: '#0d6efd'
            }]
        });
    });
</script>
@endsection
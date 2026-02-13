<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container section" style="max-width: 800px;">
    
    <div style="margin-bottom: 2rem;">
        <h1 style="font-family: var(--font-heading); margin-bottom: 0.5rem;">Sigue tu Pedido <span style="color: var(--primary);"></span></h1>
        <p class="text-muted">Tiempo estimado de llegada desde el momento de la orden: <strong><?= $delivery['duration_text'] ?></strong></p>
    </div>

    <!-- Mapa de marcador (Visual only) -->
    <div class="map-container" style="background: #e0e0e0; height: 300px; border-radius: var(--radius); margin-bottom: 2rem; position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center;">
        <!-- Mockup de un camino de mapa -->
        <div style="position: absolute; width: 100%; height: 100%; opacity: 0.3; background-image: url('https://upload.wikimedia.org/wikipedia/commons/e/ec/Map_of_Caracas.jpg'); background-size: cover; background-position: center;"></div>
        
        <div style="z-index: 2; text-align: center; background: rgba(255,255,255,0.9); padding: 1rem 2rem; border-radius: 50px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <i class="fas fa-motorcycle" style="font-size: 2rem; color: var(--primary); animation: bump 1s infinite alternate;"></i>
            <div style="font-weight: bold; margin-top: 0.5rem;">En camino a tu ubicación</div>
            <div style="font-size: 0.8rem; color: #666;"><?= $delivery['distance_text'] ?> de distancia</div>
        </div>
    </div>
    
    <style>
        @keyframes bump {
            0% { transform: translateY(0); }
            100% { transform: translateY(-5px); }
        }
    </style>

    <!-- Escalera -->
    <div class="stepper" style="display: flex; justify-content: space-between; position: relative; margin-bottom: 2rem;">
        <!-- Fondo de línea -->
        <div style="position: absolute; top: 25px; left: 0; width: 100%; height: 4px; background: #eee; z-index: 1;"></div>
        
        <!-- Paso 1 -->
        <div class="step active" style="z-index: 2; text-align: center; flex: 1;">
            <div class="step-icon" style="width: 50px; height: 50px; background: var(--success); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem; font-size: 1.2rem;">
                <i class="fas fa-check"></i>
            </div>
            <div style="font-weight: 600;">Confirmado</div>
            <div style="font-size: 0.8rem; color: #888;">Orden recibida</div>
        </div>

        <!-- Paso 2 -->
        <div class="step active" style="z-index: 2; text-align: center; flex: 1;">
            <div class="step-icon" style="width: 50px; height: 50px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem; font-size: 1.2rem;">
                <i class="fas fa-fire"></i>
            </div>
            <div style="font-weight: 600;">Preparando</div>
            <div style="font-size: 0.8rem; color: #888;">En el horno</div>
        </div>

        <!-- Paso 3 -->
        <div class="step active" style="z-index: 2; text-align: center; flex: 1;">
            <div class="step-icon" style="width: 50px; height: 50px; background: var(--accent); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem; font-size: 1.2rem; box-shadow: 0 0 0 5px rgba(255, 193, 7, 0.3);">
                <i class="fas fa-motorcycle"></i>
            </div>
            <div style="font-weight: 600;">En Camino</div>
            <div style="font-size: 0.8rem; color: #888;">Repartidor asignado</div>
        </div>
    </div>

    <!-- Botón Volver -->
    <div style="margin-top: 3rem; text-align: center;">
        <a href="javascript:history.back()" class="btn-secondary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 2rem; border-radius: 50px; border: 1px solid #ddd; color: var(--text-light); text-decoration: none; font-weight: 600; transition: all 0.3s;">
            <i class="fas fa-arrow-left"></i> Volver a la página anterior
        </a>
    </div>

</div>

<style>
    .btn-secondary:hover {
        background: #f8f9fa;
        border-color: #ccc;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
</style>

<?= $this->endSection() ?>

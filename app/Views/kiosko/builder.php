<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="section" style="padding-top: 2rem;">
    <div class="container">
        <h1 class="section-title">Arma tu Pizza Perfecta</h1>
        
        <div class="kiosk-grid">
            <div class="visualizer-container">
                <div class="pizza-stage">
                    <img src="<?= base_url('images/Pizzas/PIZZA CON SALSA DE TOMATE.png') ?>" id="dynamicPizzaImage" class="layer active" 
                         onerror="handleImageError(this)">
                </div>
            </div>

            <div class="price-tag-floating">
                $<span id="totalPriceDisplay">10.00</span>
            </div>

            <div class="controls-panel">
                <form action="<?= base_url('kiosko/add') ?>" method="post" id="pizzaForm">
                    <input type="hidden" name="total_price" id="inputTotalPrice" value="10.00">
                    <input type="hidden" name="description" id="inputDescription" value="">
                    
                    <!-- STEP 1: TAMAÑO -->
                    <div id="step-1" class="step-section active">
                        <div class="control-group">
                            <h3><i class="fas fa-expand-arrows-alt"></i> Paso 1: Elige el Tamaño</h3>
                            <div class="options-grid">
                                <?php foreach($sizes as $sz): ?>
                                <label class="option-card">
                                    <input type="radio" name="size" value="<?= $sz['id'] ?>" data-price="<?= $sz['price'] ?>" data-name="<?= $sz['name'] ?>" <?= $sz['id'] == 'size_personal' ? 'checked' : '' ?> onchange="updatePizza()">
                                    <div class="card-content">
                                        <span><?= $sz['name'] ?></span>
                                        <span class="price-badge">$<?= $sz['price'] ?></span>
                                    </div>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <button type="button" class="btn-cta btn-block" onclick="nextStep(2)">
                            Siguiente <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>

                    <!-- STEP 2: QUESO -->
                    <div id="step-2" class="step-section" style="display: none;">
                        <div class="control-group">
                            <h3><i class="fas fa-cheese"></i> Paso 2: Elige el Queso</h3>
                            <div class="options-grid">
                                <?php foreach($cheeses as $c): ?>
                                <label class="option-card">
                                    <input type="radio" name="cheese" value="<?= $c['id'] ?>" data-price="<?= $c['price'] ?>" data-name="<?= $c['name'] ?>" <?= $c['id'] == 'cheese_mozarella' ? 'checked' : '' ?> onchange="updatePizza()">
                                    <div class="card-content">
                                        <span><?= $c['name'] ?></span>
                                        <?php if($c['price'] > 0): ?>
                                        <span class="price-badge">+$<?= number_format($c['price'], 2) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="nav-buttons">
                            <button type="button" class="btn-secondary" onclick="prevStep(1)">
                                <i class="fas fa-arrow-left"></i> Atrás
                            </button>
                            <button type="button" class="btn-cta" onclick="nextStep(3)">
                                Siguiente <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 3: INGREDIENTES -->
                    <div id="step-3" class="step-section" style="display: none;">
                        <div class="control-group">
                            <h3><i class="fas fa-pizza-slice"></i> Paso 3: Agrega Ingredientes</h3>
                            <div class="options-grid">
                                
                                <label class="option-card">
                                    <input type="checkbox" name="toppings[]" value="ACEITUNAS" data-price="1.00" data-name="Aceitunas" onchange="updatePizza()">
                                    <div class="card-content"><span>Aceitunas</span><span class="price-badge">+$1.00</span></div>
                                </label>

                                <label class="option-card">
                                    <input type="checkbox" name="toppings[]" value="CEBOLLA" data-price="0.50" data-name="Cebolla" onchange="updatePizza()">
                                    <div class="card-content"><span>Cebolla</span><span class="price-badge">+$0.50</span></div>
                                </label>

                                <label class="option-card">
                                    <input type="checkbox" name="toppings[]" value="CHAMPI" data-price="1.50" data-name="Champiñones" onchange="updatePizza()">
                                    <div class="card-content"><span>Champiñones</span><span class="price-badge">+$1.50</span></div>
                                </label>

                                <label class="option-card">
                                    <input type="checkbox" name="toppings[]" value="JAMON" data-price="1.50" data-name="Jamón" onchange="updatePizza()">
                                    <div class="card-content"><span>Jamón</span><span class="price-badge">+$1.50</span></div>
                                </label>

                                <label class="option-card">
                                    <input type="checkbox" name="toppings[]" value="MAIZ" data-price="1.00" data-name="Maíz" onchange="updatePizza()">
                                    <div class="card-content"><span>Maíz</span><span class="price-badge">+$1.00</span></div>
                                </label>

                                <label class="option-card">
                                    <input type="checkbox" name="toppings[]" value="PEPERONI" data-price="1.50" data-name="Peperoni" onchange="updatePizza()">
                                    <div class="card-content"><span>Pepperoni</span><span class="price-badge">+$1.50</span></div>
                                </label> 

                                <label class="option-card">
                                    <input type="checkbox" name="toppings[]" value="PIMIENTOS" data-price="1.00" data-name="Pimientos" onchange="updatePizza()">
                                    <div class="card-content"><span>Pimientos</span><span class="price-badge">+$1.00</span></div>
                                </label>

                                <label class="option-card">
                                    <input type="checkbox" name="toppings[]" value="PIÑA" data-price="1.50" data-name="Piña" onchange="updatePizza()">
                                    <div class="card-content"><span>Piña</span><span class="price-badge">+$1.50</span></div>
                                </label>

                                <label class="option-card">
                                    <input type="checkbox" name="toppings[]" value="POLLO FRITO" data-price="2.00" data-name="Pollo" onchange="updatePizza()">
                                    <div class="card-content"><span>Pollo Frito</span><span class="price-badge">+$2.00</span></div>
                                </label>

                                <label class="option-card">
                                    <input type="checkbox" name="toppings[]" value="TOCINETA" data-price="2.00" data-name="Tocineta" onchange="updatePizza()">
                                    <div class="card-content"><span>Tocineta</span><span class="price-badge">+$2.00</span></div>
                                </label>

                            </div>
                        </div>

                        <div class="nav-buttons">
                            <button type="button" class="btn-secondary" onclick="prevStep(2)">
                                <i class="fas fa-arrow-left"></i> Atrás
                            </button>
                            <button type="submit" class="btn-cta" style="flex: 2;">
                                <i class="fas fa-shopping-cart"></i> Agregar al Carrito
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
    .kiosk-grid { 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        gap: 2rem; 
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .visualizer-container { 
        position: relative; 
        top: 0; 
        background: radial-gradient(circle, #ffffff 0%, #f0f0f0 100%); 
        border-radius: 50%; 
        padding: 1rem; 
        box-shadow: var(--shadow-md); 
        aspect-ratio: 1/1; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        width: 100%; 
        max-width: 500px;
        margin: 0 auto; 
        overflow: hidden; 
    }
    
    .pizza-stage { position: relative; width: 100%; height: 100%; transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1); }
    .layer { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain; transition: opacity 0.3s ease; }
    .base-layer { z-index: 10; }
    #dynamicPizzaImage { z-index: 20; opacity: 1; }
    
    .controls-panel { 
        background: var(--surface); 
        padding: 2rem; 
        border-radius: var(--radius); 
        box-shadow: var(--shadow-sm); 
        width: 100%; 
        max-width: 800px; 
    }
    
    .control-group { margin-bottom: 2rem; }
    .control-group h3 { margin-bottom: 1rem; font-family: var(--font-heading); border-bottom: 2px solid var(--background); padding-bottom: 0.5rem; }
    .options-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 1rem; justify-content: center; } 
    .option-card { cursor: pointer; position: relative; }
    .option-card input { display: none; }
    .card-content { border: 2px solid var(--background); padding: 1rem; border-radius: 8px; text-align: center; transition: var(--transition); display: flex; flex-direction: column; gap: 0.5rem; align-items: center; height: 100%; justify-content: center; }
    .option-card input:checked + .card-content { border-color: var(--primary); background-color: #fff5f2; box-shadow: 0 4px 10px rgba(255, 87, 34, 0.2); transform: translateY(-2px); }
    .price-badge { font-size: 0.9rem; background: var(--secondary); color: white; padding: 4px 10px; border-radius: 12px; font-weight: bold; }

    /* When toppings reach the max, disabled options look subdued */
    .option-card input:disabled + .card-content { opacity: 0.55; pointer-events: none; cursor: not-allowed; }
    
    .price-tag-floating { 
        position: relative; 
        margin-top: -3rem; 
        background: var(--primary); 
        color: white; 
        padding: 0.8rem 2rem; 
        font-size: 2.5rem; 
        font-weight: 800; 
        border-radius: 50px; 
        box-shadow: 0 8px 20px rgba(0,0,0,0.2); 
        z-index: 100; 
        border: 4px solid white;
    }
    
    .step-section { animation: fadeIn 0.4s ease; }
    .nav-buttons { display: flex; gap: 1rem; margin-top: 2rem; justify-content: space-between; }
    .btn-secondary { background: #f0f0f0; color: #333; border: 2px solid #ddd; padding: 1rem 2rem; border-radius: 8px; cursor: pointer; font-weight: bold; transition: 0.3s; font-size: 1.1rem; }
    .btn-secondary:hover { background: #e0e0e0; border-color: #ccc; }
    .btn-cta { font-size: 1.2rem; padding: 1rem 2rem; }
    
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    @media (max-width: 900px) { 
        .price-tag-floating { font-size: 1.8rem; padding: 0.5rem 1.5rem; }
    }
</style>

<script>
    // Lista de jerarquía (orden alfabético)
    const ingredientHierarchy = [
        'ACEITUNAS',
        'CEBOLLA',
        'CHAMPI',
        'JAMON',
        'MAIZ',
        'PEPERONI',
        'PIMIENTOS',
        'PIÑA',
        'POLLO FRITO',
        'TOCINETA' 
    ];

    function nextStep(step) {
        // Simple validation or just move
        showStep(step);
    }

    function prevStep(step) {
        showStep(step);
    }

    function showStep(stepNumber) {
        // Hide all steps
        document.querySelectorAll('.step-section').forEach(el => el.style.display = 'none');
        // Show target step
        const target = document.getElementById('step-' + stepNumber);
        if(target) {
            target.style.display = 'block';
            target.classList.add('active');
        }
    }

    function updatePizza() {
        let currentTotal = 0;
        let description = "Pizza Personalizada";
        
        // 1. Calcular Precio y Descripción
        const size = document.querySelector('input[name="size"]:checked');
        let sizeScale = 1.0;

        if (size) {
            currentTotal += parseFloat(size.dataset.price);
            description = "Pizza " + size.dataset.name;
            
            // Logic for sizing: 
            // Small (id likes size_personal) = 0% increase -> scale(1.0)
            // Medium (id likes size_medium) = 50% increase -> scale(1.5)
            // Large (id likes size_large) = 100% increase -> scale(2.0)
            
            if(size.value === 'size_medium') sizeScale = 1.5;
            if(size.value === 'size_large') sizeScale = 2.0;

        } else { currentTotal += 10.00; }
        
        // Apply transform
        const pizzaStage = document.querySelector('.pizza-stage');
        if(pizzaStage) {
            pizzaStage.style.transform = `scale(${sizeScale})`;
        }
        
        const cheese = document.querySelector('input[name="cheese"]:checked');
        if (cheese) {
            currentTotal += parseFloat(cheese.dataset.price);
            description += ", " + cheese.dataset.name;
        }

        const toppingsChecked = document.querySelectorAll('input[name="toppings[]"]:checked');
        let selectedToppings = [];
        
        toppingsChecked.forEach(t => {
            currentTotal += parseFloat(t.dataset.price);
            selectedToppings.push(t.value);
            description += ", " + t.dataset.name;
        });

        // 2. Construir Nombre de Imagen
        const imgElement = document.getElementById('dynamicPizzaImage');
        const basePath = "<?= base_url('images/Pizzas') ?>";
        let ingredientsSubPath = "";
        
        // Determinar base según el paso activo
        let filename = "PIZZA CON SALSA DE TOMATE.png"; // Default Step 1
        
        const step2 = document.getElementById('step-2');
        const step3 = document.getElementById('step-3');

        // Si estamos en paso 2 o 3, la base lógica es con queso
        if ((step2 && step2.style.display !== 'none') || (step3 && step3.style.display !== 'none')) {
            if (cheese && cheese.dataset && cheese.dataset.name) {
                const cName = cheese.dataset.name.toUpperCase();
                if (cName.includes('MOZ') || cName.includes('MOZZA') || cName.includes('MOZZ')) {
                    filename = "1- QUESO MOZZA.png";
                    ingredientsSubPath = "Mozarella";
                } else if (cName.includes('CHED')) {
                    filename = "1- QUESO CHEDDAR.png";
                    ingredientsSubPath = "Cheddar";
                } else if (cName.includes('PARM')) {
                    filename = "1- QUESO PARMESANO.png";
                    ingredientsSubPath = "Parmesano";
                }
            }
        }

        if (selectedToppings.length > 0) {
            // Ordenar según la jerarquía visual de tus archivos
            selectedToppings.sort((a, b) => {
                let indexA = ingredientHierarchy.indexOf(a);
                let indexB = ingredientHierarchy.indexOf(b);
                // Si no está en la lista (index -1), lo mandamos al final
                if (indexA === -1) indexA = 99;
                if (indexB === -1) indexB = 99;
                return indexA - indexB;
            });

            // Lógica de nombres según cantidad
            if (selectedToppings.length <= 2) {
                // 1 o 2 ingredientes: Empiezan con "PIZZA CON..."
                if (selectedToppings.length === 1) {
                    filename = selectedToppings[0] + ".png";
                } else {
                    filename = selectedToppings[0] + " Y " + selectedToppings[1] + ".png";
                }
            } else if (selectedToppings.length === 3) {
                // 3 ingredientes: Formato directo "A B Y C.png"
                filename = selectedToppings[0] + " " + selectedToppings[1] + " Y " + selectedToppings[2] + ".png";
            } else {
                // Más de 3: Mostrar base 
                console.log("Más de 3 ingredientes seleccionados");
            }
        }

        // Build full URL using base + optional cheese subfolder and encode filename
        let fullPath = basePath + (ingredientsSubPath ? ('/' + ingredientsSubPath) : '');
        imgElement.src = fullPath + '/' + encodeURIComponent(filename);
        console.log("Buscar: " + filename + " - Mostrando: " + imgElement.src);

        // UI
        document.getElementById('totalPriceDisplay').innerText = currentTotal.toFixed(2);
        document.getElementById('inputTotalPrice').value = currentTotal.toFixed(2);
        document.getElementById('inputDescription').value = description;
    }

    // Wrap showStep to trigger updatePizza so image changes immediately on navigation
    const originalShowStep = showStep;
    showStep = function(stepNumber) {
        // Hide all steps
        document.querySelectorAll('.step-section').forEach(el => el.style.display = 'none');
        // Show target step
        const target = document.getElementById('step-' + stepNumber);
        if(target) {
            target.style.display = 'block';
            target.classList.add('active');
        }
        // Force update to check active step and change image
        updatePizza();
    };

    // Enforce a maximum number of toppings selectable at once
    function enforceToppingLimit() {
        const MAX_TOPPINGS = 3;
        const toppings = Array.from(document.querySelectorAll('input[name="toppings[]"]'));
        if (!toppings.length) return;

        const checkedCount = toppings.filter(t => t.checked).length;

        // Disable unchecked toppings when at limit, enable otherwise
        toppings.forEach(t => {
            if (!t.checked) t.disabled = (checkedCount >= MAX_TOPPINGS);
        });

        // Safety: if somehow there are more checked than allowed, uncheck extras (keep first ones)
        if (checkedCount > MAX_TOPPINGS) {
            let keep = 0;
            for (let i = 0; i < toppings.length; i++) {
                const t = toppings[i];
                if (t.checked) {
                    keep++;
                    if (keep > MAX_TOPPINGS) t.checked = false;
                }
            }
        }
    }

    // Manejo de errores: Si la imagen calculada no existe, vuelve a la base adecuada
    function handleImageError(img) {
        console.log("Imagen no encontrada: " + img.src + ". Revertiendo a base safe.");
        // Evita bucle infinito
        if (!decodeURIComponent(img.src).includes("PIZZA CON SALSA DE TOMATE")) {
            img.src = "<?= base_url('images/Pizzas/PIZZA CON SALSA DE TOMATE.png') ?>";
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Attach change listeners to toppings to enforce the max selection
        document.querySelectorAll('input[name="toppings[]"]').forEach(i => {
            i.addEventListener('change', () => {
                enforceToppingLimit();
                updatePizza();
            });
        });

        // Initial enforcement + UI update
        enforceToppingLimit();
        updatePizza();
        showStep(1); // Ensure start at step 1
    });
</script>

<?= $this->endSection() ?>
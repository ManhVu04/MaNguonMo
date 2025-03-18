<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0"><i class="fas fa-shopping-cart me-2"></i>Giỏ hàng</h1>
        </div>
        <div class="card-body">
            <?php if (!empty($cart)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Giá</th>
                                <th style="width: 200px;">Số lượng</th>
                                <th class="text-end">Tổng</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = 0; ?>
                            <?php foreach ($cart as $id => $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if ($item['image']): ?>
                                                <img src="/webbanhang/<?php echo $item['image']; ?>" 
                                                     alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                                     class="img-thumbnail me-3" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php endif; ?>
                                            <span><?php echo htmlspecialchars($item['name']); ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo number_format($item['price'], 0, ',', '.'); ?> VND</td>
                                    <td>
                                        <div class="input-group">
                                            <button class="btn btn-outline-secondary" 
                                                    type="button" 
                                                    onclick="updateQuantity(<?php echo $id; ?>, 'decrease')">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" 
                                                   class="form-control text-center" 
                                                   value="<?php echo $item['quantity']; ?>" 
                                                   min="1"
                                                   onchange="updateQuantity(<?php echo $id; ?>, 'input', this.value)">
                                            <button class="btn btn-outline-secondary" 
                                                    type="button" 
                                                    onclick="updateQuantity(<?php echo $id; ?>, 'increase')">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <?php 
                                        $subtotal = $item['price'] * $item['quantity'];
                                        $total += $subtotal;
                                        echo number_format($subtotal, 0, ',', '.'); 
                                        ?> VND
                                    </td>
                                    <td class="text-end">
                                        <button onclick="updateQuantity(<?php echo $id; ?>, 'remove')" 
                                                class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="table-light fw-bold">
                                <td colspan="3">Tổng cộng</td>
                                <td class="text-end"><?php echo number_format($total, 0, ',', '.'); ?> VND</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <a href="/webbanhang/Product" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
                    </a>
                    <a href="/webbanhang/Product/checkout" class="btn btn-success">
                        Thanh toán<i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Giỏ hàng của bạn đang trống</p>
                    <a href="/webbanhang/Product" class="btn btn-primary mt-3">
                        <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function updateQuantity(productId, action, value = null) {
    let url = '/webbanhang/Product/updateCart';
    let data = {
        product_id: productId,
        action: action
    };
    
    if (value !== null) {
        data.value = value;
    }

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra');
    });
}
</script>

<?php include 'app/views/shares/footer.php'; ?>
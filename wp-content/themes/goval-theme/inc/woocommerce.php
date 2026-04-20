<?php
// === Funil Direto para Checkout (Pular Carrinho) ===
// Redireciona o botão "Comprar" ou "Adicionar ao Carrinho" direto para o Checkout
add_filter('woocommerce_add_to_cart_redirect', 'goval_skip_cart_redirect');
function goval_skip_cart_redirect() {
    return wc_get_checkout_url();
}

// Alterar o texto do botão de compra nas listagens para "Comprar Agora"
add_filter('woocommerce_product_add_to_cart_text', 'goval_custom_cart_button_text');
function goval_custom_cart_button_text() {
    return __('Comprar Agora', 'woocommerce');
}
// Alterar o texto do botão de compra na página do produto single
add_filter('woocommerce_product_single_add_to_cart_text', 'goval_custom_cart_button_text_single');
function goval_custom_cart_button_text_single() {
    return __('Finalizar Compra Agora', 'woocommerce');
}

// === Limpeza do Checkout (Checkout Dinâmico Estilo Modal) ===
// Vamos simplificar os campos do checkout para experiências e cursos (produtos virtuais)
add_filter('woocommerce_checkout_fields', 'goval_simplify_checkout_fields');
function goval_simplify_checkout_fields($fields) {
    // Remove notas do pedido (opcional)
    unset($fields['order']['order_comments']);
    
    // Se no futuro focarmos apenas em venda digital/voos físicos sem frete de produtos materiais, podemos remover address_2, company etc.
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_2']);
    
    return $fields;
}

// === Estilização de Classes do wp_body ===
// Adicionamos uma classe extra no body do checkout para podermos montar o nosso overlay fixo
add_filter('body_class', 'goval_checkout_body_class');
function goval_checkout_body_class($classes) {
    if (is_checkout() && !is_wc_endpoint_url('order-received')) {
        $classes[] = 'goval-dynamic-checkout';
    }
    return $classes;
}

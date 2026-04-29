<div style="font-family: sans-serif; color: #333;">
    <h2 style="color: #f97316;">Hola, {{ $order->customer_name }}!</h2>
    <p>Hemos recibido tu reserva en <strong>El Fogon Dominicano</strong>.</p>

    <div style="background: #f3f4f6; padding: 15px; border-radius: 10px; margin: 20px 0;">
        <p><strong>Numero de pedido:</strong> #FOG-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</p>
        <p><strong>Raciones:</strong> {{ $order->quantity }}</p>
        <p><strong>Hora de recogida:</strong> {{ $order->pickup_time }}</p>
    </div>

    <p>Te esperamos con la comida caliente. Si tienes cualquier duda, puedes responder a este correo.</p>
    <hr>
    <p style="font-size: 12px; color: #777;">Has recibido este correo porque realizaste una reserva en nuestra web aceptando nuestra politica de privacidad.</p>
</div>

@php $idCampo = $campo ?? 'password'; @endphp

<div id="criterios-{{ $idCampo }}"
     class="mt-3 rounded-xl border border-blue-200 bg-blue-50 p-4 transition-colors"
     aria-live="polite">

    <p class="mb-3 text-sm font-bold text-blue-900">Tu contraseña debe tener:</p>

    <ul class="space-y-2">
        <li data-regla="longitud" class="flex items-center gap-3 text-sm font-semibold text-slate-600">
            <span data-icono class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-200 text-xs font-bold text-slate-500">○</span>
            <span>Mínimo 8 caracteres</span>
        </li>
        <li data-regla="mayuscula-minuscula" class="flex items-center gap-3 text-sm font-semibold text-slate-600">
            <span data-icono class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-200 text-xs font-bold text-slate-500">○</span>
            <span>Una mayúscula y una minúscula</span>
        </li>
        <li data-regla="numero" class="flex items-center gap-3 text-sm font-semibold text-slate-600">
            <span data-icono class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-200 text-xs font-bold text-slate-500">○</span>
            <span>Un número</span>
        </li>
    </ul>
</div>

<script>
    (function () {
        var campo = document.getElementById(@js($idCampo));
        var caja = document.getElementById('criterios-' + @js($idCampo));

        if (!campo || !caja) {
            return;
        }

        var reglas = {
            'longitud': function (v) { return v.length >= 8; },
            'mayuscula-minuscula': function (v) { return /\p{Lu}/u.test(v) && /\p{Ll}/u.test(v); },
            'numero': function (v) { return /\d/.test(v); }
        };

        var filaBase = 'flex items-center gap-3 text-sm font-semibold ';
        var iconoBase = 'flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold ';
        var cajaBase = 'mt-3 rounded-xl border p-4 transition-colors ';

        var estados = {
            vacio:  { fila: 'text-slate-600', icono: 'bg-slate-200 text-slate-500', simbolo: '○' },
            falta:  { fila: 'text-red-700',   icono: 'bg-red-100 text-red-600',     simbolo: '✕' },
            cumple: { fila: 'text-green-700', icono: 'bg-green-500 text-white',     simbolo: '✓' }
        };

        function actualizar() {
            var valor = campo.value;
            var cumpleTodas = true;

            caja.querySelectorAll('li').forEach(function (li) {
                var cumple = reglas[li.dataset.regla](valor);
                var estado = valor === '' ? estados.vacio : (cumple ? estados.cumple : estados.falta);
                var icono = li.querySelector('[data-icono]');

                li.className = filaBase + estado.fila;
                icono.className = iconoBase + estado.icono;
                icono.textContent = estado.simbolo;

                if (!cumple) {
                    cumpleTodas = false;
                }
            });

            caja.className = cajaBase + (cumpleTodas
                ? 'border-green-300 bg-green-50'
                : 'border-blue-200 bg-blue-50');
        }

        campo.addEventListener('input', actualizar);
        actualizar();
    })();
</script>
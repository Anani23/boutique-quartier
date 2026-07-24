@extends('layouts.app')

@section('title', 'Nouvelle vente')

@section('content')
<h4 class="mb-4">Nouvelle vente</h4>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">Produits disponibles</div>
            <div class="card-body">
                <div class="d-flex gap-2 mb-3">
                    <input type="text" id="recherche" class="form-control" placeholder="Rechercher un produit...">
                    <button type="button" class="btn btn-outline-primary flex-shrink-0" data-bs-toggle="modal" data-bs-target="#modal-scanner">
                        <i class="ti ti-qrcode"></i> Scanner
                    </button>
                </div>
                <div id="liste-produits" style="max-height: 420px; overflow-y: auto;"></div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">Panier</div>
            <div class="card-body">
                <form method="POST" action="{{ route('ventes.store') }}" id="form-vente">
                    @csrf
                    <table class="table">
                        <thead><tr><th>Produit</th><th>Qté</th><th>Sous-total</th><th></th></tr></thead>
                        <tbody id="panier-body">
                            <tr id="panier-vide"><td colspan="4" class="text-center text-muted py-3">Panier vide</td></tr>
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between fs-5 fw-bold border-top pt-3">
                        <span>Total</span>
                        <span id="total-panier">0 FCFA</span>
                    </div>
                    <button class="btn btn-success w-100 mt-3" id="btn-valider" disabled>Valider la vente</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="modal-scanner" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scanner un produit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="qr-reader" style="width: 100%;"></div>
                <div id="qr-resultat" class="text-center mt-3 fw-bold"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
const produits = {!! json_encode($produitsJson) !!};

let panier = {};

function formatFcfa(n) {
    return new Intl.NumberFormat('fr-FR').format(Math.round(n)) + ' FCFA';
}

function renderListe(filtre = '') {
    const conteneur = document.getElementById('liste-produits');
    conteneur.innerHTML = '';
    produits
        .filter(p => p.nom.toLowerCase().includes(filtre.toLowerCase()))
        .forEach(p => {
            const div = document.createElement('div');
            div.className = 'd-flex justify-content-between align-items-center border-bottom py-2';
            div.innerHTML = `
                <div>
                    <div>${p.nom}</div>
                    <small class="text-muted">${formatFcfa(p.prix)} · stock ${p.stock}</small>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary">Ajouter</button>
            `;
            div.querySelector('button').addEventListener('click', () => ajouterAuPanier(p));
            conteneur.appendChild(div);
        });
}

function ajouterAuPanier(produit) {
    const dejaDansPanier = panier[produit.id]?.quantite ?? 0;
    if (dejaDansPanier + 1 > produit.stock) {
        alert('Stock insuffisant pour ' + produit.nom);
        return;
    }
    if (!panier[produit.id]) {
        panier[produit.id] = { ...produit, quantite: 0 };
    }
    panier[produit.id].quantite += 1;
    renderPanier();
}

function changerQuantite(id, delta) {
    const item = panier[id];
    const nouvelleQte = item.quantite + delta;
    if (nouvelleQte <= 0) {
        delete panier[id];
    } else if (nouvelleQte > item.stock) {
        alert('Stock insuffisant pour ' + item.nom);
        return;
    } else {
        item.quantite = nouvelleQte;
    }
    renderPanier();
}

function renderPanier() {
    const body = document.getElementById('panier-body');
    const entries = Object.values(panier);
    body.innerHTML = '';

    if (entries.length === 0) {
        body.innerHTML = '<tr id="panier-vide"><td colspan="4" class="text-center text-muted py-3">Panier vide</td></tr>';
    }

    let total = 0;
    entries.forEach(item => {
        const sousTotal = item.quantite * item.prix;
        total += sousTotal;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${item.nom}</td>
            <td>
                <div class="input-group input-group-sm" style="width: 110px;">
                    <button type="button" class="btn btn-outline-secondary" data-action="dec">-</button>
                    <input type="text" class="form-control text-center" value="${item.quantite}" readonly>
                    <button type="button" class="btn btn-outline-secondary" data-action="inc">+</button>
                </div>
            </td>
            <td>${formatFcfa(sousTotal)}</td>
            <td><button type="button" class="btn btn-sm btn-outline-danger" data-action="del">×</button></td>
        `;
        tr.querySelector('[data-action="inc"]').addEventListener('click', () => changerQuantite(item.id, 1));
        tr.querySelector('[data-action="dec"]').addEventListener('click', () => changerQuantite(item.id, -1));
        tr.querySelector('[data-action="del"]').addEventListener('click', () => { delete panier[item.id]; renderPanier(); });
        body.appendChild(tr);
    });

    document.getElementById('total-panier').textContent = formatFcfa(total);
    document.getElementById('btn-valider').disabled = entries.length === 0;
}

document.getElementById('recherche').addEventListener('input', e => renderListe(e.target.value));

document.getElementById('form-vente').addEventListener('submit', function (e) {
    const form = e.target;
    document.querySelectorAll('input[name^="lignes"]').forEach(el => el.remove());
    Object.values(panier).forEach((item, i) => {
        const inputId = document.createElement('input');
        inputId.type = 'hidden';
        inputId.name = `lignes[${i}][produit_id]`;
        inputId.value = item.id;
        form.appendChild(inputId);

        const inputQte = document.createElement('input');
        inputQte.type = 'hidden';
        inputQte.name = `lignes[${i}][quantite]`;
        inputQte.value = item.quantite;
        form.appendChild(inputQte);
    });
});

renderListe();

// --- Scanner QR ---
let html5QrCode = null;
let dernierCodeScanne = null;
let dernierScanTimestamp = 0;

function surCodeDecode(codeTexte) {
    const maintenant = Date.now();
    if (codeTexte === dernierCodeScanne && (maintenant - dernierScanTimestamp) < 2500) {
        return; // évite d'ajouter plusieurs fois le même produit en continu devant la caméra
    }
    dernierCodeScanne = codeTexte;
    dernierScanTimestamp = maintenant;

    const resultat = document.getElementById('qr-resultat');
    const match = /^PROD-(\d+)$/.exec(codeTexte.trim());
    const produit = match ? produits.find(p => p.id === parseInt(match[1], 10)) : null;

    if (!produit) {
        resultat.className = 'text-center mt-3 fw-bold text-danger';
        resultat.textContent = 'Produit inconnu ou indisponible pour ce QR.';
        return;
    }

    ajouterAuPanier(produit);
    resultat.className = 'text-center mt-3 fw-bold text-success';
    resultat.textContent = 'Ajouté : ' + produit.nom;
}

const modalScanner = document.getElementById('modal-scanner');
modalScanner.addEventListener('shown.bs.modal', () => {
    document.getElementById('qr-resultat').textContent = '';
    html5QrCode = new Html5Qrcode('qr-reader');
    html5QrCode.start(
        { facingMode: 'environment' },
        { fps: 10, qrbox: 220 },
        surCodeDecode,
        () => {}
    ).catch(() => {
        document.getElementById('qr-resultat').innerHTML =
            '<span class="text-danger">Impossible d\'accéder à la caméra. Vérifiez les autorisations de votre navigateur.</span>';
    });
});

modalScanner.addEventListener('hidden.bs.modal', () => {
    if (html5QrCode) {
        html5QrCode.stop().then(() => html5QrCode.clear()).catch(() => {});
        html5QrCode = null;
    }
});
</script>
@endpush

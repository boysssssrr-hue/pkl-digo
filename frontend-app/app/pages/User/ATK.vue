<template>
	<div class="atk-page" :class="[isDark ? 'theme-dark' : 'theme-light']">
		<!-- BACKGROUND ADAPTIF RAK ALAT TULIS DENGAN NUANSA HIJAU -->
		<div class="atk-bg-backdrop" aria-hidden="true"></div>

		<!-- TOP BAR NAVIGASI TERPADU -->
		<UserTopbar />

		<!-- MAIN CONTAINER -->
		<main class="atk-main">
			<!-- TOP HEADER & QUICK SCAN ACTION -->
			<div class="atk-header-row">
				<div class="atk-header-card">
					<div class="atk-badge-kicker">
						<span>✦ Portal Pengambilan Pegawai</span>
						<span class="division-pill">Divisi {{ userDivisionName }}</span>
					</div>
					<h1 class="atk-title">Alat Tulis Kantor (ATK)</h1>
					<p class="atk-subtitle">
						Pilih barang ATK dari inventaris atau pindai barcode fisik menggunakan kamera.
					</p>
				</div>

				<div class="atk-header-actions">
					<!-- TOMBOL KERANJANG BELANJA (CART) -->
					<button
						type="button"
						class="btn-cart-action"
						@click="isCartOpen = true"
						:class="{ 'has-items': cartCount > 0 }"
						:title="`Buka Keranjang Belanja (${cartCount} barang)`"
					>
						<div class="cart-btn-icon-box">
							<svg viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor">
								<path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
							</svg>
							<span v-if="cartCount > 0" class="cart-badge-count">{{ cartCount }}</span>
						</div>
						<span>Keranjang <b v-if="cartCount > 0">({{ cartTotalUnits }} unit)</b></span>
					</button>

					<!-- TOMBOL SCAN QR CODE KAMERA -->
					<button type="button" class="btn-scan-action" @click="startCamera">
						<svg viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor">
							<path d="M4 4h3l2-2h6l2 2h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2zm8 3a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 2a3 3 0 1 1 0 6 3 3 0 0 1 0-6z"/>
						</svg>
						<span>Scan QR Code Kamera</span>
					</button>

					<!-- TOMBOL SINKRON DATA -->
					<button
						type="button"
						class="btn-sync-action"
						@click="fetchProducts"
						:disabled="isLoading"
						title="Sinkronkan Data dengan Database"
					>
						<svg viewBox="0 0 24 24" class="w-4 h-4" :class="{ 'animate-spin': isLoading }" fill="currentColor">
							<path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46A7.93 7.93 0 0 0 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74A7.93 7.93 0 0 0 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z"/>
						</svg>
						<span>{{ isLoading ? 'Memuat...' : 'Sinkron' }}</span>
					</button>
				</div>
			</div>

			<!-- COMPACT STATS SUMMARY -->
			<div class="stats-compact-row">
				<div class="stat-pill-card">
					<div class="stat-pill-header">
						<span class="stat-icon-emoji">📦</span>
						<span class="stat-pill-label">Total Barang</span>
					</div>
					<strong class="stat-pill-value">{{ items.length }} Jenis</strong>
					<small class="stat-pill-sub text-green">{{ totalStockUnits }} total unit</small>
				</div>
				<div class="stat-pill-card">
					<div class="stat-pill-header">
						<span class="stat-icon-emoji">🏢</span>
						<span class="stat-pill-label">Pengambilan Divisi</span>
					</div>
					<strong class="stat-pill-value">{{ divisionTakenCount }} Unit</strong>
					<small class="stat-pill-sub text-amber">Semester ini</small>
				</div>
				<div class="stat-pill-card">
					<div class="stat-pill-header">
						<span class="stat-icon-emoji">📊</span>
						<span class="stat-pill-label">Batas Kuota</span>
					</div>
					<strong class="stat-pill-value">Per Divisi</strong>
					<small class="stat-pill-sub text-blue">Maks 2-5 item/transaksi</small>
				</div>
			</div>

			<!-- SUCCESS / REDIRECT ALERT BANNER -->
			<div v-if="redirectMessage" class="redirect-alert-banner">
				<span class="pulse-icon">✓</span>
				<span>{{ redirectMessage }}</span>
			</div>

			<!-- CONTROLS ROW: DROPDOWN KATEGORI & LIVE SEARCH BAR -->
			<div class="controls-filter-bar">
				<!-- DROPDOWN PILIHAN KATEGORI (SESUAI REQUEST USER) -->
				<div class="control-dropdown-wrap">
					<svg class="control-icon" viewBox="0 0 24 24" fill="currentColor">
						<path d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z"/>
					</svg>
					<select v-model="selectedCategory" class="table-select-input">
						<option v-for="cat in categories" :key="cat" :value="cat">
							{{ cat === 'Semua' ? 'Semua Kategori' : cat }}
						</option>
					</select>
					<svg class="dropdown-chevron" viewBox="0 0 20 20" fill="currentColor">
						<path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
					</svg>
				</div>

				<!-- SEARCH INPUT -->
				<div class="search-input-wrap">
					<svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<circle cx="11" cy="11" r="8"></circle>
						<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
					</svg>
					<input
						v-model="searchQuery"
						type="text"
						placeholder="Cari nama barang, kode, atau barcode..."
						class="search-input"
					/>
					<button v-if="searchQuery" @click="searchQuery = ''" class="clear-search-btn" type="button" title="Bersihkan">✕</button>
				</div>
			</div>

			<!-- TABEL INVENTARIS ATK (CLEAN & MINIMALIST) -->
			<section class="table-card">
				<div class="table-card-header">
					<div class="table-card-title-group">
						<h2 class="table-card-title">INVENTARIS ALAT TULIS KANTOR</h2>
						<span class="table-count-badge">{{ filteredItems.length }} Item</span>
					</div>
					<small class="text-subtle text-xs">Pilih barang untuk mengajukan pengambilan</small>
				</div>

				<!-- DESKTOP TABLE -->
				<div class="hidden sm:block table-responsive-box">
					<table class="minimal-table">
						<thead>
							<tr>
								<th class="th-num">#</th>
								<th>Kode &amp; QR Code</th>
								<th>Nama Barang &amp; Spesifikasi</th>
								<th>Kategori</th>
								<th>Stok Gudang</th>
								<th>Batas Divisi</th>
								<th class="text-right">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<tr v-if="isLoading">
								<td colspan="7" class="empty-table-cell">
									<div class="loading-state">
										<span class="spinner"></span>
										<span>Menghubungkan ke database dan memuat daftar ATK...</span>
									</div>
								</td>
							</tr>

							<tr v-else-if="filteredItems.length === 0">
								<td colspan="7" class="empty-table-cell">
									{{ searchQuery ? `Tidak ada data ATK yang cocok dengan pencarian "${searchQuery}".` : 'Belum ada data barang ATK dalam sistem.' }}
								</td>
							</tr>

							<tr v-else v-for="(item, idx) in filteredItems" :key="item.id" class="table-row">
								<td class="td-num">{{ idx + 1 }}</td>
								<td>
									<div class="code-barcode-wrap">
										<strong class="font-mono text-code">{{ item.code }}</strong>
										<div class="barcode-sub-row">
											<span class="font-mono text-barcode">{{ item.barcode }}</span>
											<button
												type="button"
												class="btn-barcode-icon"
												@click.stop="openBarcodeModal(item)"
												title="Lihat & Cetak QR Code Resmi"
											>
												<svg viewBox="0 0 24 24" class="w-3.5 h-3.5" fill="currentColor">
													<path d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm13-2h3v2h-3v-2zm-3 2h2v2h-2v-2zm3 3h3v3h-3v-3zm-5-1h2v4h-2v-4zm2 2h3v2h-3v-2z"/>
												</svg>
												<span>QR Code</span>
											</button>
										</div>
									</div>
								</td>
								<td>
									<div class="product-cell">
										<div class="product-thumb">
											<img
												:src="item.image || '/images/atk/default-atk.svg'"
												:alt="item.name"
												class="product-img"
												width="44"
												height="44"
												decoding="async"
												@error="handleImgError($event, '/images/atk/default-atk.svg')"
											/>
										</div>
										<div class="product-info">
											<strong class="product-name">{{ item.name }}</strong>
											<span class="product-desc">{{ item.desc }}</span>
										</div>
									</div>
								</td>
								<td>
									<span class="category-pill">{{ item.category }}</span>
								</td>
								<td>
									<span
										class="status-pill"
										:class="item.stock <= 0 ? 'pill-red' : (item.stock <= 5 ? 'pill-amber' : 'pill-green')"
									>
										{{ item.stock }} {{ item.unit }}
									</span>
								</td>
								<td>
									<span class="limit-badge">
										Maks {{ item.maxPerDivisi }} {{ item.unit }}
									</span>
								</td>
								<td class="text-right">
									<button
										type="button"
										class="btn-row-action"
										:class="{ 'action-active': item.stock > 0 }"
										:disabled="item.stock <= 0"
										@click="openRequestModal(item)"
										:title="item.stock > 0 ? `Ambil Barang: ${item.name}` : 'Stok Barang Habis'"
									>
										<svg v-if="item.stock > 0" viewBox="0 0 24 24" class="w-3.5 h-3.5 mr-1" fill="currentColor">
											<path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
										</svg>
										<span>{{ item.stock > 0 ? 'Ambil' : 'Habis' }}</span>
									</button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<!-- MOBILE CARDS LIST (SM:HIDDEN) -->
				<div class="sm:hidden divide-y divide-slate-800/40">
					<div v-if="isLoading" class="p-6 text-center text-xs text-subtle">
						<div class="spinner mx-auto mb-2"></div>
						<span>Memuat daftar ATK...</span>
					</div>

					<div v-else-if="filteredItems.length === 0" class="p-6 text-center text-xs text-subtle">
						{{ searchQuery ? `Tidak ada data ATK yang cocok dengan "${searchQuery}".` : 'Belum ada data barang ATK.' }}
					</div>

					<div v-else v-for="(item, idx) in filteredItems" :key="item.id" class="p-3.5 space-y-2.5">
						<div class="flex items-start gap-3">
							<div class="w-12 h-12 rounded-xl bg-slate-950/60 border border-slate-800 flex items-center justify-center p-1 shrink-0 overflow-hidden">
								<img
									:src="item.image || '/images/atk/default-atk.svg'"
									:alt="item.name"
									class="w-full h-full object-contain"
									@error="handleImgError($event, '/images/atk/default-atk.svg')"
								/>
							</div>
							<div class="flex-1 min-w-0">
								<div class="flex items-center justify-between gap-1 mb-0.5">
									<span class="category-pill text-[10px]">{{ item.category }}</span>
									<span
										class="status-pill text-[10px]"
										:class="item.stock <= 0 ? 'pill-red' : (item.stock <= 5 ? 'pill-amber' : 'pill-green')"
									>
										{{ item.stock }} {{ item.unit }}
									</span>
								</div>
								<strong class="text-xs font-bold block truncate" :class="isDark ? 'text-white' : 'text-slate-900'">{{ item.name }}</strong>
								<p class="text-[10px] text-slate-400 truncate">{{ item.desc }}</p>
							</div>
						</div>

						<!-- Code & limit details row -->
						<div class="flex items-center justify-between text-[11px] pt-1 border-t border-slate-800/30">
							<div class="flex items-center gap-1.5 font-mono text-[10px] text-emerald-400">
								<span>{{ item.code }}</span>
								<span class="text-slate-500">•</span>
								<span class="text-slate-400">{{ item.barcode }}</span>
							</div>
							<span class="limit-badge text-[10px]">Maks: {{ item.maxPerDivisi }} {{ item.unit }}/divisi</span>
						</div>

						<!-- Action Buttons -->
						<div class="flex items-center gap-2 pt-1">
							<button
								type="button"
								class="btn-barcode-icon px-2.5 py-1.5 rounded-lg border border-slate-700 text-[11px] font-semibold text-slate-300 flex items-center gap-1 cursor-pointer shrink-0"
								@click.stop="openBarcodeModal(item)"
								title="Lihat Label QR Code"
							>
								<svg viewBox="0 0 24 24" class="w-3.5 h-3.5" fill="currentColor">
									<path d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm13-2h3v2h-3v-2zm-3 2h2v2h-2v-2zm3 3h3v3h-3v-3zm-5-1h2v4h-2v-4zm2 2h3v2h-3v-2z"/>
								</svg>
								<span>QR</span>
							</button>

							<button
								type="button"
								class="btn-row-action flex-1 py-2 text-center flex items-center justify-center gap-1.5 cursor-pointer"
								:class="{ 'action-active': item.stock > 0 }"
								:disabled="item.stock <= 0"
								@click="openRequestModal(item)"
							>
								<svg v-if="item.stock > 0" viewBox="0 0 24 24" class="w-3.5 h-3.5" fill="currentColor">
									<path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
								</svg>
								<span>{{ item.stock > 0 ? 'Ambil Barang Ini' : 'Stok Habis' }}</span>
							</button>
						</div>
					</div>
				</div>
			</section>
		</main>

		<!-- ================================================================= -->
		<!-- MODAL CAMERA SCANNER (QR CODE ONLY - MINIMALIS & BEBAS MIRROR)    -->
		<!-- ================================================================= -->
		<div v-if="isScanning" class="modal-backdrop" @click.self="stopCamera">
			<div class="modal-card-box camera-card">
				<div class="modal-box-header">
					<div class="title-with-icon">
						<svg viewBox="0 0 24 24" class="w-5 h-5 text-emerald" fill="currentColor">
							<path d="M4 4h3l2-2h6l2 2h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2zm8 3a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 2a3 3 0 1 1 0 6 3 3 0 0 1 0-6z"/>
						</svg>
						<div>
							<h3 class="modal-title">Pemindai QR Code Kamera</h3>
							<small class="text-subtle">Arahkan kamera langsung ke kode QR barang</small>
						</div>
					</div>
					<button class="btn-close-modal" type="button" @click="stopCamera">✕</button>
				</div>

				<div class="camera-viewfinder-box">
					<!-- Container for HTML5-QRCode Reader -->
					<div id="atk-qr-reader" class="html5-qr-container"></div>
					<video v-if="!usingHtml5Qrcode" ref="videoRef" class="camera-video-feed" playsinline muted></video>

					<!-- KOTAK BIDIK SCANNER PRESISI DI TENGAH SESUAI UKURAN KAMERA -->
					<div class="scanner-custom-overlay">
						<div class="scanner-target-box">
							<div class="corner top-left"></div>
							<div class="corner top-right"></div>
							<div class="corner bottom-left"></div>
							<div class="corner bottom-right"></div>
							<div class="scanner-laser-line"></div>
						</div>
						<div class="scanner-guide-text">
							<span>Arahkan QR Code ke Kotak Tengah</span>
						</div>
					</div>
				</div>

				<div v-if="scanError" class="camera-error-banner">
					<p>{{ scanError }}</p>
				</div>
			</div>
		</div>

		<!-- ================================================================= -->
		<!-- MODAL LIHAT & CETAK QR CODE ATK (QR CODE ONLY)                    -->
		<!-- ================================================================= -->
		<div v-if="barcodeItem" class="modal-backdrop" @click.self="barcodeItem = null">
			<div class="modal-card-box barcode-modal-card">
				<div class="modal-box-header">
					<div class="title-with-icon">
						<span class="text-xl">🏷️</span>
						<div>
							<h3 class="modal-title">Label QR Code Resmi Barang</h3>
							<small class="text-subtle">QR Code tetap siap dicetak atau dipindai</small>
						</div>
					</div>
					<button class="btn-close-modal" type="button" @click="barcodeItem = null">✕</button>
				</div>

				<div class="barcode-preview-body">
					<!-- Printable Ticket Label -->
					<div class="barcode-ticket-card">
						<div class="ticket-header">
							<span class="ticket-brand">SIMPEL • BPJS KESEHATAN</span>
							<span class="ticket-category">{{ barcodeItem.category }}</span>
						</div>

						<div class="ticket-product-row">
							<img
								:src="barcodeItem.image || '/images/atk/default-atk.svg'"
								:alt="barcodeItem.name"
								class="ticket-thumb"
								@error="handleImgError($event, '/images/atk/default-atk.svg')"
							/>
							<div class="ticket-product-info">
								<h4 class="ticket-name">{{ barcodeItem.name }}</h4>
								<div class="ticket-meta-grid">
									<span>Kode: <b>{{ barcodeItem.barcode }}</b></span>
									<span>Satuan: <b>{{ barcodeItem.unit }}</b></span>
									<span>Stok: <b>{{ barcodeItem.stock }}</b></span>
								</div>
							</div>
						</div>

						<!-- QR Code Section (Standar Satu Macam: QR Code Saja) -->
						<div class="ticket-qr-main-box">
							<img
								:src="`/images/barcodes/qr-${barcodeItem.barcode}.svg`"
								:alt="`QR ${barcodeItem.barcode}`"
								class="ticket-qr-large-img"
							/>
							<div class="ticket-qr-badge">QR CODE RESMI BARANG</div>
							<span class="ticket-qr-hint">Scan via Kamera Scanner / HP</span>
						</div>
					</div>

					<!-- Action Buttons -->
					<div class="barcode-modal-actions">
						<button
							type="button"
							class="btn-print-barcode"
							@click="printBarcodeLabel(barcodeItem)"
						>
							🖨️ Cetak / Print Stiker Label QR Code
						</button>
						<a
							:href="`/images/barcodes/qr-${barcodeItem.barcode}.svg`"
							:download="`QR-${barcodeItem.barcode}.svg`"
							class="btn-download-barcode"
						>
							📥 Unduh QR Code (SVG)
						</a>
						<button
							type="button"
							class="btn-quick-request"
							@click="requestFromBarcode(barcodeItem)"
						>
							⚡ Ajukan Pengambilan Barang
						</button>
					</div>
				</div>
			</div>
		</div>

		<!-- ================================================================= -->
		<!-- MODAL FORM PENGAJUAN PENGAMBILAN ATK (DENGAN 3 TOMBOL KONFIRMASI)  -->
		<!-- ================================================================= -->
		<div v-if="selectedItem" class="modal-backdrop" @click.self="closeRequestModal">
			<div class="modal-card-box request-card" role="dialog" aria-modal="true">
				<div class="modal-box-header">
					<div class="title-with-icon">
						<span class="text-xl">📋</span>
						<div>
							<h3 class="modal-title">Form Pengambilan Barang</h3>
							<small class="text-subtle">Pilih jumlah & keperluan untuk barang yang dipilih / discan</small>
						</div>
					</div>
					<button class="btn-close-modal" type="button" @click="closeRequestModal">✕</button>
				</div>

				<div class="modal-product-header">
					<div class="modal-img-wrapper">
						<img
							:src="selectedItem.image || '/images/atk/default-atk.svg'"
							:alt="selectedItem.name"
							class="modal-img-thumb"
							@error="handleImgError($event, '/images/atk/default-atk.svg')"
						/>
					</div>
					<div class="modal-product-title-wrap">
						<span class="barcode-tag font-mono">{{ selectedItem.code }} • {{ selectedItem.barcode }}</span>
						<h2 class="modal-item-title">{{ selectedItem.name }}</h2>
						<p class="category-text">{{ selectedItem.category }} • {{ selectedItem.desc }}</p>
					</div>
				</div>

				<!-- DIVISION LIMIT WARNING BOX -->
				<div class="division-rule-alert">
					<span class="rule-icon">⚠️</span>
					<div>
						<strong>Aturan Kuota Divisi {{ userDivisionName }}:</strong>
						<p>Batas maksimal pengambilan item ini adalah <b>{{ selectedItem.maxPerDivisi }} {{ selectedItem.unit }}</b> per transaksi. Stok tersedia: <b>{{ selectedItem.stock }} {{ selectedItem.unit }}</b>.</p>
					</div>
				</div>

				<!-- CART NOTICE JIKA BARANG INI SUDAH ADA DI KERANJANG -->
				<div v-if="currentItemInCartQty > 0" class="cart-exist-alert">
					<span class="exist-icon">🛒</span>
					<div>
						<strong>Sudah ada {{ currentItemInCartQty }} {{ selectedItem.unit }} di Keranjang</strong>
						<p>Menambahkan lagi akan diakumulasikan ke keranjang belanja Anda (Sisa batas kuota: {{ maxAllowedForSelectedItem }} {{ selectedItem.unit }}).</p>
					</div>
				</div>

				<form @submit.prevent="finishFromModal" class="request-form">
					<div class="form-grid-two">
						<label class="form-label">
							<span>Divisi Pemohon</span>
							<input type="text" :value="`Divisi ${userDivisionName}`" readonly disabled class="input-readonly" />
						</label>

						<label class="form-label">
							<span>Nama Pemohon (NIP)</span>
							<input type="text" :value="`${currentUser.name} (${currentUser.nip || '02614'})`" readonly disabled class="input-readonly" />
						</label>
					</div>

					<label class="form-label">
						<span>Jumlah Pengambilan (Maks: {{ Math.min(selectedItem.stock, selectedItem.maxPerDivisi) }} {{ selectedItem.unit }})</span>
						<div class="qty-stepper-input-group">
							<button
								type="button"
								class="btn-qty-step"
								@click="quantity = Math.max(1, quantity - 1)"
								:disabled="quantity <= 1 || isSubmitting"
							>
								-
							</button>
							<input
								v-model.number="quantity"
								type="number"
								min="1"
								:max="Math.min(selectedItem.stock, selectedItem.maxPerDivisi)"
								required
								class="input-qty-stepped font-mono"
							/>
							<button
								type="button"
								class="btn-qty-step"
								@click="quantity = Math.min(Math.min(selectedItem.stock, selectedItem.maxPerDivisi), quantity + 1)"
								:disabled="quantity >= Math.min(selectedItem.stock, selectedItem.maxPerDivisi) || isSubmitting"
							>
								+
							</button>
							<span class="qty-unit-label">{{ selectedItem.unit }}</span>
						</div>
					</label>

					<label class="form-label">
						<span>Keperluan / Catatan Penggunaan</span>
						<textarea
							v-model="note"
							rows="2"
							placeholder="Tuliskan alasan/keperluan penggunaan barang ini..."
							required
							class="textarea-note"
						></textarea>
					</label>

					<!-- PETUNJUK 3 TOMBOL -->
					<div class="action-help-text">
						<span class="help-dot">✦</span>
						<span>Pilih <b>"Masukkan ke Keranjang"</b> jika ingin scan/ambil barang lain, atau <b>"Selesai"</b> untuk langsung mengajukan.</span>
					</div>

					<!-- 3 TOMBOL SESUAI PERMINTAAN USER: BATAL, MASUKKAN KE KERANJANG, SELESAI -->
					<div class="modal-actions-cart-group">
						<button
							class="btn-modal-cancel"
							type="button"
							@click="closeRequestModal"
							:disabled="isSubmitting"
						>
							✕ Batal
						</button>

						<div class="modal-actions-right">
							<button
								class="btn-modal-add-cart"
								type="button"
								@click="addToCartFromModal"
								:disabled="isSubmitting"
							>
								<svg viewBox="0 0 24 24" class="w-4 h-4 mr-1.5" fill="currentColor">
									<path d="M11 9h2V6h3V4h-3V1h-2v3H8v2h3v3zm-4 9c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2zm-9.83-3.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.86-7.01L19.42 4h-.01l-1.1 2-2.76 5H8.53l-.13-.27L6.16 6l-.95-2-.94-2H1v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.13 0-.25-.11-.25-.25z"/>
								</svg>
								<span>Masukkan ke Keranjang</span>
							</button>

							<button
								class="btn-modal-finish"
								type="submit"
								:disabled="isSubmitting"
							>
								<span v-if="isSubmitting">Memproses...</span>
								<span v-else>
									✓ Selesai <span v-if="cartCount > 0" class="badge-in-btn">({{ cartCount + 1 }} Barang)</span>
								</span>
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>

		<!-- ================================================================= -->
		<!-- MODAL KERANJANG BELANJA (CART DRAWER / MODAL)                     -->
		<!-- ================================================================= -->
		<div v-if="isCartOpen" class="modal-backdrop" @click.self="isCartOpen = false">
			<div class="modal-card-box cart-modal-card" role="dialog" aria-modal="true">
				<div class="modal-box-header">
					<div class="title-with-icon">
						<div class="cart-modal-icon-badge">
							🛒
						</div>
						<div>
							<h3 class="modal-title">Keranjang Pengambilan ATK</h3>
							<small class="text-subtle">{{ cartCount }} jenis barang dipilih (Total {{ cartTotalUnits }} unit)</small>
						</div>
					</div>
					<button class="btn-close-modal" type="button" @click="isCartOpen = false">✕</button>
				</div>

				<div class="cart-modal-body">
					<!-- Alert informatif: Semua barang akan digabung jadi 1 transaksi -->
					<div class="cart-info-banner">
						<span class="info-badge-icon">💡</span>
						<p>Semua barang di keranjang ini akan diajukan sekaligus dan <b>menjadi satu di riwayat</b> pengambilan Anda.</p>
					</div>

					<!-- Empty State -->
					<div v-if="cart.length === 0" class="cart-empty-state">
						<div class="cart-empty-icon">🛒</div>
						<h4 class="cart-empty-title">Keranjang Belanja Masih Kosong</h4>
						<p class="cart-empty-desc">Pilih barang dari daftar inventaris atau scan QR code kamera untuk memasukkan barang ke keranjang.</p>
						<button type="button" class="btn-continue-shopping" @click="isCartOpen = false">
							Mulai Pilih / Scan Barang →
						</button>
					</div>

					<!-- Cart Items List -->
					<div v-else class="cart-items-scroll-list">
						<div v-for="cItem in cart" :key="cItem.product_id" class="cart-item-row">
							<img
								:src="cItem.product.image || '/images/atk/default-atk.svg'"
								:alt="cItem.product.name"
								class="cart-item-img"
								@error="handleImgError($event, '/images/atk/default-atk.svg')"
							/>
							<div class="cart-item-info">
								<div class="cart-item-header">
									<strong class="cart-item-name">{{ cItem.product.name }}</strong>
									<button
										type="button"
										class="btn-remove-cart-item"
										@click="removeFromCart(cItem.product_id)"
										title="Hapus dari keranjang"
									>
										🗑️
									</button>
								</div>
								<span class="cart-item-code font-mono">{{ cItem.product.code }} • {{ cItem.product.category }}</span>

								<!-- Quantity Controls -->
								<div class="cart-item-qty-row">
									<div class="qty-stepper">
										<button
											type="button"
											class="btn-step"
											@click="updateCartQty(cItem, -1)"
											title="Kurangi"
										>
											-
										</button>
										<span class="qty-number font-mono">{{ cItem.quantity }}</span>
										<button
											type="button"
											class="btn-step"
											@click="updateCartQty(cItem, 1)"
											:disabled="cItem.quantity >= Math.min(cItem.product.stock, cItem.product.maxPerDivisi)"
											title="Tambah"
										>
											+
										</button>
									</div>
									<span class="qty-unit">{{ cItem.product.unit }} (Maks: {{ Math.min(cItem.product.stock, cItem.product.maxPerDivisi) }})</span>
								</div>

								<!-- Note input inline -->
								<div class="cart-item-note-wrap">
									<input
										v-model="cItem.note"
										type="text"
										placeholder="Catatan keperluan barang ini..."
										class="cart-note-input"
										@change="saveCartToStorage"
									/>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Cart Modal Footer -->
				<div v-if="cart.length > 0" class="cart-modal-footer">
					<div class="cart-summary-line">
						<span class="summary-label">Total Pengambilan:</span>
						<strong class="summary-val font-mono">{{ cartCount }} Jenis • {{ cartTotalUnits }} Unit</strong>
					</div>

					<div class="cart-footer-buttons">
						<button
							type="button"
							class="btn-clear-cart"
							@click="clearCart"
							:disabled="isSubmitting"
						>
							Kosongkan
						</button>
						<button
							type="button"
							class="btn-continue-picking"
							@click="isCartOpen = false"
							:disabled="isSubmitting"
						>
							+ Tambah / Scan Lain
						</button>
						<button
							type="button"
							class="btn-checkout-cart"
							@click="checkoutCart"
							:disabled="isSubmitting"
						>
							<span v-if="isSubmitting">Memproses...</span>
							<span v-else>Selesaikan Pengambilan ({{ cartCount }}) →</span>
						</button>
					</div>
				</div>
			</div>
		</div>

		<!-- ================================================================= -->
		<!-- FLOATING CART BAR (MUNCUL JIKA ADA BARANG DI KERANJANG)           -->
		<!-- ================================================================= -->
		<div v-if="cartCount > 0 && !isCartOpen" class="floating-cart-badge-bar">
			<button type="button" class="floating-cart-btn" @click="isCartOpen = true">
				<div class="floating-cart-icon-circle">
					🛒
					<span class="floating-count">{{ cartCount }}</span>
				</div>
				<div class="floating-cart-texts">
					<span class="floating-title">Keranjang Pengambilan ATK</span>
					<span class="floating-subtitle">{{ cartCount }} jenis barang ({{ cartTotalUnits }} unit)</span>
				</div>
				<span class="floating-arrow">Buka Keranjang & Selesai →</span>
			</button>
		</div>

		<!-- ================================================================= -->
		<!-- FLOATING TOAST NOTIFICATION (FEEDBACK KETIKA BARANG DITAMBAHKAN)  -->
		<!-- ================================================================= -->
		<div v-if="cartToast" class="cart-floating-toast" :class="`toast-${cartToast.type}`">
			<span class="toast-icon">{{ cartToast.type === 'success' ? '✓' : 'ℹ️' }}</span>
			<span class="toast-text">{{ cartToast.message }}</span>
			<button type="button" class="toast-btn-cart" @click="isCartOpen = true">Lihat Keranjang ({{ cartCount }})</button>
			<button type="button" class="toast-close" @click="cartToast = null">✕</button>
		</div>
	</div>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue'

const router = useRouter()
const config = useRuntimeConfig()
const apiBase = config.public?.apiBase || 'http://127.0.0.1:8000/api'

// Global Shared Theme State (synced with UserTopbar)
const isDark = useState('user_theme_dark', () => true)

const searchQuery = ref('')
const selectedCategory = ref('Semua')
const selectedItem = ref(null)
const quantity = ref(1)
const note = ref('')
const isSubmitting = ref(false)
const isLoading = ref(false)
const redirectMessage = ref('')

// ============================================================================
// SHOPPING CART STATE & HELPERS (SISTEM KERANJANG PENGAMBILAN ATK)
// ============================================================================
const cart = ref([])
const isCartOpen = ref(false)
const cartToast = ref(null)
let cartToastTimer = null

const showToast = (message, type = 'success') => {
	if (cartToastTimer) clearTimeout(cartToastTimer)
	cartToast.value = { message, type }
	cartToastTimer = setTimeout(() => {
		cartToast.value = null
	}, 4500)
}

const loadCartFromStorage = () => {
	if (typeof window === 'undefined') return
	try {
		const raw = localStorage.getItem('user_atk_cart')
		if (raw) {
			const parsed = JSON.parse(raw)
			if (Array.isArray(parsed)) {
				cart.value = parsed
			}
		}
	} catch (e) {}
}

const saveCartToStorage = () => {
	if (typeof window === 'undefined') return
	try {
		localStorage.setItem('user_atk_cart', JSON.stringify(cart.value))
	} catch (e) {}
}

const cartCount = computed(() => cart.value.length)
const cartTotalUnits = computed(() => cart.value.reduce((acc, c) => acc + (Number(c.quantity) || 0), 0))

// Cek apakah item yang sedang dibuka di modal sudah ada di keranjang
const currentItemInCartQty = computed(() => {
	if (!selectedItem.value) return 0
	const existing = cart.value.find(c => c.product_id === selectedItem.value.id)
	return existing ? (Number(existing.quantity) || 0) : 0
})

const maxAllowedForSelectedItem = computed(() => {
	if (!selectedItem.value) return 1
	const maxLimit = Math.min(selectedItem.value.stock, selectedItem.value.maxPerDivisi)
	return Math.max(1, maxLimit - currentItemInCartQty.value)
})

// User Profile & Division
const userCookie = useCookie('auth_user')
const tokenCookie = useCookie('auth_token')
const currentUser = ref({ name: 'Pegawai', nip: '02614', division: { name: 'Kepesertaan' } })

const userAvatarLetter = computed(() => (currentUser.value.name || 'P').charAt(0).toUpperCase())
const userDivisionName = computed(() => currentUser.value.division?.name || 'Kepesertaan')

const handleImgError = (e, fallback = '/images/atk/default-atk.svg') => {
	if (e && e.target) {
		e.target.onerror = null
		e.target.src = fallback
	}
}

// Categories
const categories = ['Semua', 'Alat Tulis', 'Buku & Kertas', 'Folder & Arsip', 'Perlengkapan']

// Items Inventory List with Division Limits and Barcodes
const items = ref([])

const fetchProducts = async () => {
	isLoading.value = true
	try {
		const activeToken = tokenCookie.value || (typeof window !== 'undefined' ? localStorage.getItem('auth_token') : null)
		const headers = { 'Accept': 'application/json' }
		if (activeToken) {
			headers['Authorization'] = `Bearer ${activeToken}`
		}

		const res = await $fetch(`${apiBase}/products`, { headers }).catch(() => null)

		if (Array.isArray(res)) {
			let limitsMap = {}
			if (currentUser.value?.id) {
				try {
					const limitsRes = await $fetch(`${apiBase}/users/${currentUser.value.id}/limits`, { headers }).catch(() => null)
					if (Array.isArray(limitsRes)) {
						limitsRes.forEach(l => {
							limitsMap[l.product_id] = l.quantity_limit
						})
					}
				} catch (err) {}
			}

			items.value = res.map((p, idx) => ({
				id: p.id || idx + 1,
				code: p.barcode || `ATK-${String(idx + 1).padStart(3, '0')}`,
				barcode: p.barcode || `ATK-${String(idx + 1).padStart(3, '0')}`,
				name: p.name,
				category: p.category || 'Alat Tulis',
				stock: p.stock ?? 0,
				maxPerDivisi: limitsMap[p.id] ?? (p.max_per_division || 5),
				unit: p.unit || 'Pcs',
				image: p.image || `/images/atk/default-atk.svg`,
				icon: '📦',
				tone: 'green',
				desc: p.description || 'Peralatan operasional kantor'
			}))
		} else {
			items.value = []
		}
	} catch (e) {
		console.warn('Gagal memuat data ATK dari API:', e)
		items.value = []
	} finally {
		isLoading.value = false
	}
}

onMounted(() => {
	if (typeof window !== 'undefined') {
		const savedTheme = localStorage.getItem('user_theme')
		if (savedTheme) {
			isDark.value = savedTheme === 'dark'
		}
	}

	if (userCookie.value) {
		try {
			const u = typeof userCookie.value === 'string' ? JSON.parse(userCookie.value) : userCookie.value
			if (u && u.name) currentUser.value = u
		} catch (e) {}
	}
	if (!currentUser.value.nip && typeof window !== 'undefined') {
		const s = localStorage.getItem('auth_user')
		if (s) {
			try {
				const u = JSON.parse(s)
				if (u && u.name) currentUser.value = u
			} catch (e) {}
		}
	}
	loadCartFromStorage()
	fetchProducts()

	if (typeof window !== 'undefined') {
		window.addEventListener('focus', fetchProducts)
	}
})

const totalStockUnits = computed(() => items.value.reduce((acc, i) => acc + i.stock, 0))
const divisionTakenCount = computed(() => 14)

// Filtered Catalog based on Category Dropdown & Search Query
const filteredItems = computed(() => {
	const q = searchQuery.value.toLowerCase().trim()
	const cat = selectedCategory.value

	return items.value.filter(item => {
		if (cat !== 'Semua' && (item.category || '').toLowerCase() !== cat.toLowerCase()) return false
		if (!q) return true
		const searchHaystack = [item.name, item.code, item.barcode, item.category, item.desc].join(' ').toLowerCase()
		return searchHaystack.includes(q)
	})
})

// Barcode Viewer Modal State
const barcodeItem = ref(null)

const openBarcodeModal = (item) => {
	barcodeItem.value = item
}

const requestFromBarcode = (item) => {
	barcodeItem.value = null
	openRequestModal(item)
}

const printBarcodeLabel = (item) => {
	if (typeof window === 'undefined') return
	const printWindow = window.open('', '_blank', 'width=550,height=650')
	if (!printWindow) {
		alert('Pop-up terblokir oleh browser. Harap izinkan pop-up untuk mencetak QR Code.')
		return
	}
	const qrSvg = `/images/barcodes/qr-${item.barcode}.svg`

	printWindow.document.write(`
		<!DOCTYPE html>
		<html>
		<head>
			<title>Label QR Code - ${item.barcode} - ${item.name}</title>
			<style>
				@page { size: auto; margin: 8mm; }
				body {
					font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
					background: #ffffff;
					color: #0f172a;
					display: flex;
					flex-direction: column;
					align-items: center;
					justify-content: center;
					padding: 24px;
					margin: 0;
				}
				.label-card {
					border: 2px dashed #0f172a;
					border-radius: 16px;
					padding: 24px;
					width: 320px;
					text-align: center;
					box-sizing: border-box;
				}
				.header-tag {
					font-size: 11px;
					font-weight: 800;
					letter-spacing: 2px;
					color: #059669;
					margin-bottom: 6px;
				}
				.item-name {
					font-size: 15px;
					font-weight: bold;
					margin: 0 0 16px;
					color: #0f172a;
					line-height: 1.3;
				}
				.qr-wrap {
					display: flex;
					align-items: center;
					justify-content: center;
					margin: 12px 0;
				}
				.qr-img {
					width: 170px;
					height: 170px;
				}
				.meta-text {
					background: #f8fafc;
					border-radius: 8px;
					padding: 10px;
					font-size: 12px;
					color: #475569;
					line-height: 1.6;
					margin-top: 10px;
				}
				.meta-text b { color: #0f172a; }
				.footer-note {
					font-size: 9px;
					color: #94a3b8;
					margin-top: 14px;
				}
			</style>
		</head>
		<body>
			<div class="label-card">
				<div class="header-tag">INVENTARIS ATK BPJS KESEHATAN</div>
				<h3 class="item-name">${item.name}</h3>
				<div class="qr-wrap">
					<img src="${qrSvg}" class="qr-img" alt="QR Code" />
				</div>
				<div class="meta-text">
					<div>Kode: <b>${item.barcode}</b></div>
					<div>Kategori: <b>${item.category}</b></div>
					<div>Satuan: <b>${item.unit || 'Pcs'}</b></div>
				</div>
				<div class="footer-note">Dicetak dari Sistem SIMPEL • Pindai Menggunakan Kamera Scanner</div>
			</div>
			<script>
				window.onload = function() {
					window.print();
				};
			<\/script>
		</body>
		</html>
	`)
	printWindow.document.close()
}

// Camera Barcode/QR Scanner
const isScanning = ref(false)
const usingHtml5Qrcode = ref(false)
const videoRef = ref(null)
const mediaStream = ref(null)
const scanError = ref('')
let animationFrameId = null
let barcodeDetector = null
let html5QrScannerInstance = null

const startCamera = async () => {
	scanError.value = ''
	isScanning.value = true

	try {
		const { Html5Qrcode, Html5QrcodeSupportedFormats } = await import('html5-qrcode')
		usingHtml5Qrcode.value = true
		await nextTick()

		setTimeout(async () => {
			try {
				// Bersihkan instans lama jika ada sebelum inisialisasi baru
				if (html5QrScannerInstance) {
					try {
						if (html5QrScannerInstance.isScanning) {
							await html5QrScannerInstance.stop()
						}
						await html5QrScannerInstance.clear()
					} catch (e) {}
					html5QrScannerInstance = null
				}

				// Buat instans baru pada elemen DOM yang baru saja di-mount
				html5QrScannerInstance = new Html5Qrcode('atk-qr-reader', {
					formatsToSupport: [
						Html5QrcodeSupportedFormats.QR_CODE,
						Html5QrcodeSupportedFormats.CODE_128,
						Html5QrcodeSupportedFormats.EAN_13,
						Html5QrcodeSupportedFormats.CODE_39
					],
					verbose: false
				})

				// Deteksi kamera terbaik (kamera belakang pada smartphone, default pada laptop)
				let cameraConfig = { facingMode: 'environment' }
				try {
					const devices = await Html5Qrcode.getCameras()
					if (devices && devices.length > 0) {
						const backCam = devices.find(d => /back|rear|belakang/i.test(d.label))
						cameraConfig = backCam ? backCam.id : devices[0].id
					}
				} catch (camErr) {}

				const qrConfig = {
					fps: 20,
					disableFlip: true // TIDAK MIRROR KAMERA
				}

				await html5QrScannerInstance.start(
					cameraConfig,
					qrConfig,
					(decodedText) => {
						handleBarcodeScanned(decodedText)
					},
					() => {}
				)
			} catch (startErr) {
				console.warn('Html5Qrcode start failed, falling back to native camera:', startErr)
				usingHtml5Qrcode.value = false
				startNativeCameraFallback()
			}
		}, 180)
	} catch (e) {
		console.warn('html5-qrcode module load failed, using native camera:', e)
		usingHtml5Qrcode.value = false
		startNativeCameraFallback()
	}
}

const startNativeCameraFallback = async () => {
	try {
		const stream = await navigator.mediaDevices.getUserMedia({
			video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } }
		})
		mediaStream.value = stream
		if (videoRef.value) {
			videoRef.value.srcObject = stream
			await videoRef.value.play()
		}

		if (typeof window !== 'undefined' && 'BarcodeDetector' in window) {
			barcodeDetector = new window.BarcodeDetector({
				formats: ['qr_code']
			})
			startBarcodeDetection()
		}
	} catch (err) {
		console.warn('Gagal membuka kamera fallback:', err)
		scanError.value = 'Kamera tidak dapat diakses atau izin ditolak. Harap izinkan akses kamera di browser Anda.'
	}
}

const stopCamera = async () => {
	if (html5QrScannerInstance) {
		try {
			if (html5QrScannerInstance.isScanning) {
				await html5QrScannerInstance.stop()
			}
			await html5QrScannerInstance.clear()
		} catch (e) {}
		html5QrScannerInstance = null
	}
	if (animationFrameId) {
		cancelAnimationFrame(animationFrameId)
		animationFrameId = null
	}
	if (mediaStream.value) {
		mediaStream.value.getTracks().forEach(track => track.stop())
		mediaStream.value = null
	}
	isScanning.value = false
	usingHtml5Qrcode.value = false
}

const startBarcodeDetection = () => {
	const detectFrame = async () => {
		if (!isScanning.value || !videoRef.value || !barcodeDetector) return
		try {
			if (videoRef.value.readyState === videoRef.value.HAVE_ENOUGH_DATA) {
				const barcodes = await barcodeDetector.detect(videoRef.value)
				if (barcodes.length > 0) {
					const rawValue = barcodes[0].rawValue
					handleBarcodeScanned(rawValue)
					return
				}
			}
		} catch (e) {
			console.warn('Kesalahan scanner:', e)
		}
		animationFrameId = requestAnimationFrame(detectFrame)
	}
	animationFrameId = requestAnimationFrame(detectFrame)
}

const playBeepSound = () => {
	try {
		const AudioCtx = window.AudioContext || window.webkitAudioContext
		if (!AudioCtx) return
		const audioCtx = new AudioCtx()
		const osc = audioCtx.createOscillator()
		const gain = audioCtx.createGain()
		osc.type = 'sine'
		osc.frequency.setValueAtTime(880, audioCtx.currentTime)
		gain.gain.setValueAtTime(0.15, audioCtx.currentTime)
		osc.connect(gain)
		osc.connect(audioCtx.destination)
		osc.start()
		osc.stop(audioCtx.currentTime + 0.15)
	} catch (e) {}
}

const handleBarcodeScanned = (code) => {
	playBeepSound()
	stopCamera()

	const cleanCode = (code || '').trim().toLowerCase()
	const matched = items.value.find(i =>
		(i.barcode && i.barcode.toLowerCase() === cleanCode) ||
		(i.code && i.code.toLowerCase() === cleanCode) ||
		(i.barcode && cleanCode.includes(i.barcode.toLowerCase())) ||
		(i.barcode && i.barcode.toLowerCase().includes(cleanCode)) ||
		(i.name && i.name.toLowerCase().includes(cleanCode))
	)

	if (matched) {
		selectedItem.value = matched
		quantity.value = 1
		note.value = ''
	} else {
		alert(`Barcode "${code}" tidak ditemukan dalam sistem inventaris ATK.`)
	}
}

const closeRequestModal = () => {
	selectedItem.value = null
	quantity.value = 1
	note.value = ''
}

const openRequestModal = (item) => {
	selectedItem.value = item
	quantity.value = 1
	note.value = ''
}

// 1. Tombol "Masukkan ke Keranjang"
const addToCartFromModal = () => {
	if (!selectedItem.value) return
	if (quantity.value < 1) {
		alert('Jumlah pengambilan minimal 1.')
		return
	}
	const maxLimit = Math.min(selectedItem.value.stock, selectedItem.value.maxPerDivisi)
	const existingIdx = cart.value.findIndex(c => c.product_id === selectedItem.value.id)
	const currentInCart = existingIdx >= 0 ? (Number(cart.value[existingIdx].quantity) || 0) : 0

	if (currentInCart + quantity.value > maxLimit) {
		alert(`Total di keranjang (${currentInCart + quantity.value} ${selectedItem.value.unit}) melebihi batas kuota/stok maksimal (${maxLimit} ${selectedItem.value.unit}).`)
		return
	}

	if (existingIdx >= 0) {
		cart.value[existingIdx].quantity += quantity.value
		if (note.value) {
			cart.value[existingIdx].note = note.value
		}
	} else {
		cart.value.push({
			product_id: selectedItem.value.id,
			product: {
				id: selectedItem.value.id,
				name: selectedItem.value.name,
				code: selectedItem.value.code,
				barcode: selectedItem.value.barcode,
				stock: selectedItem.value.stock,
				maxPerDivisi: selectedItem.value.maxPerDivisi,
				unit: selectedItem.value.unit,
				image: selectedItem.value.image,
				category: selectedItem.value.category,
				desc: selectedItem.value.desc
			},
			quantity: quantity.value,
			note: note.value || 'Keperluan operasional kantor'
		})
	}

	saveCartToStorage()
	const itemName = selectedItem.value.name
	const itemQty = quantity.value
	const itemUnit = selectedItem.value.unit
	closeRequestModal()

	showToast(`"${itemName}" (${itemQty} ${itemUnit}) berhasil dimasukkan ke keranjang belanja!`, 'success')
}

// 2. Tombol "Selesai" (langsung konfirmasi semua barang di keranjang + barang saat ini)
const finishFromModal = async () => {
	if (!selectedItem.value) return
	if (quantity.value < 1) {
		alert('Jumlah pengambilan minimal 1.')
		return
	}
	const maxLimit = Math.min(selectedItem.value.stock, selectedItem.value.maxPerDivisi)
	const existingIdx = cart.value.findIndex(c => c.product_id === selectedItem.value.id)
	const currentInCart = existingIdx >= 0 ? (Number(cart.value[existingIdx].quantity) || 0) : 0

	if (currentInCart + quantity.value > maxLimit) {
		alert(`Total (${currentInCart + quantity.value} ${selectedItem.value.unit}) melebihi batas kuota/stok maksimal (${maxLimit} ${selectedItem.value.unit}).`)
		return
	}

	// Masukkan barang aktif ke keranjang terlebih dahulu
	if (existingIdx >= 0) {
		cart.value[existingIdx].quantity += quantity.value
		if (note.value) {
			cart.value[existingIdx].note = note.value
		}
	} else {
		cart.value.push({
			product_id: selectedItem.value.id,
			product: {
				id: selectedItem.value.id,
				name: selectedItem.value.name,
				code: selectedItem.value.code,
				barcode: selectedItem.value.barcode,
				stock: selectedItem.value.stock,
				maxPerDivisi: selectedItem.value.maxPerDivisi,
				unit: selectedItem.value.unit,
				image: selectedItem.value.image,
				category: selectedItem.value.category,
				desc: selectedItem.value.desc
			},
			quantity: quantity.value,
			note: note.value || 'Keperluan operasional kantor'
		})
	}
	saveCartToStorage()
	closeRequestModal()

	// Langsung checkout semua barang di keranjang menjadi 1 transaksi
	await checkoutCart()
}

// 3. Eksekusi Checkout Seluruh Isi Keranjang (Menjadi 1 Transaksi di Database & Riwayat)
const checkoutCart = async () => {
	if (cart.value.length === 0) {
		alert('Keranjang belanja masih kosong.')
		return
	}

	isSubmitting.value = true

	const cartSnapshot = [...cart.value]
	const itemsPayload = cartSnapshot.map(c => ({
		product_id: c.product_id,
		quantity: c.quantity
	}))

	const combinedNotes = cartSnapshot
		.map(c => `${c.product.name} (${c.quantity} ${c.product.unit}): ${c.note || 'Pengambilan ATK'}`)
		.join('; ')

	const requestNoteFull = `${combinedNotes} (Divisi ${userDivisionName.value})`
	let borrowingResponse = null

	try {
		const activeToken = tokenCookie.value || (typeof window !== 'undefined' ? localStorage.getItem('auth_token') : null)
		const headers = { 'Accept': 'application/json' }
		if (activeToken) {
			headers['Authorization'] = `Bearer ${activeToken}`
		}

		borrowingResponse = await $fetch(`${apiBase}/borrowings`, {
			method: 'POST',
			headers,
			body: {
				user_id: currentUser.value.id || 1,
				request_note: requestNoteFull,
				items: itemsPayload
			}
		})
	} catch (apiErr) {
		console.warn('Gagal mengirim peminjaman ke API, mencatat cadangan lokal:', apiErr)
	}

	// Kurangi stok di katalog lokal
	cartSnapshot.forEach(c => {
		const found = items.value.find(i => i.id === c.product_id)
		if (found) {
			found.stock = Math.max(0, found.stock - c.quantity)
		}
	})

	// Format riwayat lokal dengan struktur array items lengkap agar satu di riwayat
	const today = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })
	const totalUnits = cartSnapshot.reduce((acc, c) => acc + (Number(c.quantity) || 0), 0)
	const primaryItemName = cartSnapshot.map(c => c.product.name).join(', ')

	const newHistoryEntry = {
		id: borrowingResponse?.data?.id || Date.now(),
		date: today,
		created_at: new Date().toISOString(),
		item: primaryItemName,
		quantity: totalUnits,
		unit: cartSnapshot.length === 1 ? cartSnapshot[0].product.unit : 'Unit',
		note: requestNoteFull,
		request_note: requestNoteFull,
		status: 'Selesai',
		statusClass: 'done',
		items: cartSnapshot.map(c => ({
			id: c.product_id,
			borrowing_id: borrowingResponse?.data?.id || Date.now(),
			product_id: c.product_id,
			quantity: c.quantity,
			product: {
				id: c.product.id,
				name: c.product.name,
				code: c.product.code,
				barcode: c.product.barcode,
				unit: c.product.unit,
				category: c.product.category,
				image: c.product.image
			}
		}))
	}

	if (typeof window !== 'undefined') {
		const existingStr = localStorage.getItem('user_atk_history')
		let existingList = []
		if (existingStr) {
			try { existingList = JSON.parse(existingStr) } catch (e) {}
		}
		existingList.unshift(newHistoryEntry)
		localStorage.setItem('user_atk_history', JSON.stringify(existingList))

		// Bersihkan keranjang di storage
		localStorage.removeItem('user_atk_cart')
	}

	// Reset keranjang
	cart.value = []
	isCartOpen.value = false

	redirectMessage.value = `Pengambilan ${cartSnapshot.length} macam barang (${totalUnits} unit) berhasil diselesaikan! Masuk ke riwayat & laporan...`

	setTimeout(() => {
		isSubmitting.value = false
		router.push('/User/riwayat')
	}, 900)
}

const updateCartQty = (cItem, delta) => {
	const newQty = (Number(cItem.quantity) || 1) + delta
	if (newQty <= 0) {
		removeFromCart(cItem.product_id)
		return
	}
	const maxLimit = Math.min(cItem.product.stock, cItem.product.maxPerDivisi)
	if (newQty > maxLimit) {
		alert(`Maksimal pengambilan untuk ${cItem.product.name} adalah ${maxLimit} ${cItem.product.unit}.`)
		return
	}
	cItem.quantity = newQty
	saveCartToStorage()
}

const removeFromCart = (productId) => {
	cart.value = cart.value.filter(c => c.product_id !== productId)
	saveCartToStorage()
	showToast('Barang berhasil dihapus dari keranjang belanja.', 'info')
}

const clearCart = () => {
	if (confirm('Apakah Anda yakin ingin mengosongkan keranjang belanja?')) {
		cart.value = []
		saveCartToStorage()
		showToast('Keranjang belanja telah dikosongkan.', 'info')
	}
}

onBeforeUnmount(() => {
	stopCamera()
	if (typeof window !== 'undefined') {
		window.removeEventListener('focus', fetchProducts)
	}
})
</script>

<style scoped>
/* ========================================================================== */
/* COLOR SYSTEM & THEME TOKENS (DARK & LIGHT)                                */
/* ========================================================================== */
.atk-page {
	position: relative;
	min-height: 100vh;
	font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
	transition: background-color 0.25s ease, color 0.25s ease;
	background-color: var(--bg-page);
	color: var(--text-body);
	overflow-x: hidden;
}

/* BACKGROUND RAK ALAT TULIS (TETAP NATURAL, TANPA LAPISAN GELAP/TERANG) */
.atk-bg-backdrop {
	position: fixed;
	inset: 0;
	background-image: url('/images/atk-bg.jpg');
	background-size: cover;
	background-position: center right;
	background-repeat: no-repeat;
	pointer-events: none;
	z-index: 0;
	opacity: 1;
}

/* THEME DARK: KOTAK TRANSPARAN & GARIS HIJAU NYALA SEPERTI LAMPU */
.theme-dark {
	--bg-page: transparent;
	--bg-card: rgba(11, 19, 32, 0.52);
	--border-card: #10b981;
	--border-table: rgba(16, 185, 129, 0.45);
	--border-row: rgba(16, 185, 129, 0.22);
	--card-shadow: 0 0 22px rgba(16, 185, 129, 0.38), inset 0 0 16px rgba(16, 185, 129, 0.08);
	--text-heading: #ffffff;
	--text-body: #f8fafc;
	--text-muted: #cbd5e1;
	--text-subtle: #94a3b8;
	--bg-input: rgba(5, 10, 20, 0.75);
	--border-input: rgba(16, 185, 129, 0.55);
	--row-hover: rgba(16, 185, 129, 0.12);
	--th-bg: rgba(11, 19, 32, 0.75);
	--chip-green-bg: rgba(34, 197, 94, 0.25);
	--chip-green-text: #4ade80;
	--chip-amber-bg: rgba(245, 158, 11, 0.25);
	--chip-amber-text: #fbbf24;
	--chip-red-bg: rgba(239, 68, 68, 0.25);
	--chip-red-text: #f87171;
	--chip-slate-bg: rgba(148, 163, 184, 0.2);
	--chip-slate-text: #cbd5e1;
	--modal-bg: rgba(11, 19, 32, 0.92);
	--modal-backdrop: rgba(0, 0, 0, 0.75);
}

/* THEME LIGHT: KOTAK TRANSPARAN & GARIS WARNA BIASA */
.theme-light {
	--bg-page: transparent;
	--bg-card: rgba(255, 255, 255, 0.65);
	--border-card: rgba(203, 213, 225, 0.9);
	--border-table: #edf2f7;
	--border-row: #f1f5f9;
	--card-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
	--text-heading: #0f172a;
	--text-body: #1e293b;
	--text-muted: #475569;
	--text-subtle: #64748b;
	--bg-input: rgba(255, 255, 255, 0.85);
	--border-input: #cbd5e1;
	--row-hover: rgba(16, 185, 129, 0.05);
	--th-bg: rgba(255, 255, 255, 0.85);
	--chip-green-bg: #dcfce7;
	--chip-green-text: #16a34a;
	--chip-amber-bg: #fef3c7;
	--chip-amber-text: #d97706;
	--chip-red-bg: #fee2e2;
	--chip-red-text: #dc2626;
	--chip-slate-bg: #f1f5f9;
	--chip-slate-text: #64748b;
	--modal-bg: rgba(255, 255, 255, 0.95);
	--modal-backdrop: rgba(15, 23, 42, 0.45);
}

/* MAIN CONTAINER */
.atk-main {
	position: relative;
	z-index: 1;
	max-width: 1280px;
	margin: 0 auto;
	padding: 20px 16px 48px;
}

@media (min-width: 640px) {
	.atk-main {
		padding: 24px 24px 56px;
	}
}

@media (min-width: 1024px) {
	.atk-main {
		padding: 28px 32px 64px;
	}
}

/* ========================================================================== */
/* HEADER ROW & ACTIONS                                                      */
/* ========================================================================== */
.atk-header-row {
	display: flex;
	flex-direction: column;
	gap: 16px;
	margin-bottom: 20px;
}

@media (min-width: 768px) {
	.atk-header-row {
		flex-direction: row;
		align-items: center;
		justify-content: space-between;
	}
}

.atk-header-card {
	padding: 14px 18px;
	border-radius: 14px;
	background-color: var(--bg-card);
	border: 1px solid var(--border-card);
	box-shadow: var(--card-shadow);
	backdrop-filter: blur(16px);
	-webkit-backdrop-filter: blur(16px);
	transition: all 0.25s ease;
}

.atk-badge-kicker {
	display: flex;
	align-items: center;
	gap: 8px;
	margin-bottom: 4px;
	font-size: 11px;
	font-weight: 700;
	color: #10b981;
}

.division-pill {
	padding: 2px 8px;
	border-radius: 9999px;
	font-size: 10px;
	font-weight: 600;
	background: var(--chip-green-bg);
	color: var(--chip-green-text);
}

.atk-title {
	font-size: 24px;
	font-weight: 800;
	letter-spacing: -0.02em;
	color: var(--text-heading);
	margin: 0 0 4px;
}

.atk-subtitle {
	font-size: 13px;
	color: var(--text-muted);
	margin: 0;
}

.atk-header-actions {
	display: flex;
	align-items: center;
	gap: 10px;
	flex-wrap: wrap;
}

/* TOMBOL KERANJANG BELANJA */
.btn-cart-action {
	display: inline-flex;
	align-items: center;
	gap: 8px;
	padding: 8px 15px;
	border-radius: 10px;
	font-size: 13px;
	font-weight: 700;
	color: var(--text-heading);
	background: var(--bg-card);
	border: 1.5px solid var(--border-card);
	cursor: pointer;
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
	transition: all 0.2s ease;
	position: relative;
}

.btn-cart-action:hover {
	border-color: #10b981;
	transform: translateY(-1px);
	box-shadow: 0 4px 14px rgba(16, 185, 129, 0.25);
}

.btn-cart-action.has-items {
	border-color: #10b981;
	background: rgba(16, 185, 129, 0.12);
	color: #10b981;
}

.cart-btn-icon-box {
	position: relative;
	display: flex;
	align-items: center;
	justify-content: center;
}

.cart-badge-count {
	position: absolute;
	top: -9px;
	right: -10px;
	background: #ef4444;
	color: #ffffff;
	font-size: 10px;
	font-weight: 900;
	width: 18px;
	height: 18px;
	border-radius: 9999px;
	display: flex;
	align-items: center;
	justify-content: center;
	box-shadow: 0 2px 6px rgba(239, 68, 68, 0.5);
	animation: popIn 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

@keyframes popIn {
	0% { transform: scale(0); }
	100% { transform: scale(1); }
}

.btn-scan-action {
	display: inline-flex;
	align-items: center;
	gap: 8px;
	padding: 9px 16px;
	border-radius: 10px;
	font-size: 13px;
	font-weight: 700;
	color: #ffffff;
	background: linear-gradient(135deg, #10b981, #059669);
	border: none;
	cursor: pointer;
	box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
	transition: all 0.15s ease;
}

.btn-scan-action:hover {
	transform: translateY(-1px);
	box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35);
}

.btn-sync-action {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	padding: 8px 14px;
	border-radius: 10px;
	font-size: 13px;
	font-weight: 600;
	background-color: var(--bg-card);
	border: 1px solid var(--border-input);
	color: var(--text-muted);
	cursor: pointer;
	transition: all 0.15s ease;
}

.btn-sync-action:hover {
	color: var(--text-heading);
	border-color: #10b981;
}

/* ========================================================================== */
/* STATS COMPACT ROW                                                          */
/* ========================================================================== */
.stats-compact-row {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
	gap: 12px;
	margin-bottom: 20px;
}

@media (min-width: 1024px) {
	.stats-compact-row {
		grid-template-columns: repeat(3, minmax(0, 1fr));
		gap: 16px;
		margin-bottom: 24px;
	}
}

.stat-pill-card {
	background-color: var(--bg-card);
	border: 1px solid var(--border-card);
	border-radius: 12px;
	padding: 12px 16px;
	display: flex;
	flex-direction: column;
	gap: 2px;
	backdrop-filter: blur(16px);
	-webkit-backdrop-filter: blur(16px);
	box-shadow: var(--card-shadow);
	transition: all 0.25s ease;
}

.stat-pill-header {
	display: flex;
	align-items: center;
	gap: 6px;
}

.stat-icon-emoji {
	font-size: 14px;
}

.stat-pill-label {
	font-size: 11px;
	color: var(--text-muted);
	font-weight: 600;
}

.stat-pill-value {
	font-size: 16px;
	font-weight: 800;
	color: var(--text-heading);
}

.stat-pill-sub {
	font-size: 10px;
	font-weight: 600;
}

.text-green { color: #10b981; }
.text-amber { color: #f59e0b; }
.text-blue { color: #3b82f6; }
.text-red { color: #ef4444; }

/* REDIRECT BANNER */
.redirect-alert-banner {
	display: flex;
	align-items: center;
	gap: 10px;
	padding: 12px 16px;
	border-radius: 10px;
	background: var(--chip-green-bg);
	border: 1px solid rgba(34, 197, 94, 0.3);
	color: var(--chip-green-text);
	font-size: 13px;
	font-weight: 600;
	margin-bottom: 20px;
}

/* ========================================================================== */
/* CONTROLS FILTER BAR (DROPDOWN + SEARCH)                                   */
/* ========================================================================== */
.controls-filter-bar {
	display: flex;
	flex-direction: column;
	gap: 12px;
	margin-bottom: 20px;
}

@media (min-width: 768px) {
	.controls-filter-bar {
		flex-direction: row;
		align-items: center;
		justify-content: space-between;
	}
}

/* DROPDOWN KATEGORI */
.control-dropdown-wrap {
	position: relative;
	display: flex;
	align-items: center;
}

.control-icon {
	position: absolute;
	left: 12px;
	width: 16px;
	height: 16px;
	color: var(--text-muted);
	pointer-events: none;
}

.table-select-input {
	appearance: none;
	-webkit-appearance: none;
	background-color: var(--bg-card);
	border: 1px solid var(--border-input);
	color: var(--text-heading);
	font-size: 13px;
	font-weight: 600;
	padding: 8px 36px 8px 34px;
	border-radius: 10px;
	cursor: pointer;
	outline: none;
	transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

.table-select-input:focus {
	border-color: #10b981;
	box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
}

.dropdown-chevron {
	position: absolute;
	right: 10px;
	width: 16px;
	height: 16px;
	color: var(--text-muted);
	pointer-events: none;
}

/* SEARCH INPUT */
.search-input-wrap {
	position: relative;
	display: flex;
	align-items: center;
	min-width: 220px;
	flex: 1;
}

@media (min-width: 768px) {
	.search-input-wrap {
		flex: initial;
		width: 320px;
	}
}

.search-icon {
	position: absolute;
	left: 12px;
	width: 15px;
	height: 15px;
	color: var(--text-muted);
	pointer-events: none;
}

.search-input {
	width: 100%;
	background-color: var(--bg-card);
	border: 1px solid var(--border-input);
	color: var(--text-heading);
	font-size: 13px;
	padding: 8px 30px 8px 34px;
	border-radius: 10px;
	outline: none;
	transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

.search-input::placeholder {
	color: var(--text-muted);
}

.search-input:focus {
	border-color: #10b981;
	box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
}

.clear-search-btn {
	position: absolute;
	right: 10px;
	background: none;
	border: none;
	color: var(--text-muted);
	font-size: 12px;
	cursor: pointer;
	padding: 2px 4px;
}

/* ========================================================================== */
/* TABLE CARD                                                                */
/* ========================================================================== */
.table-card {
	background-color: var(--bg-card);
	border: 1px solid var(--border-card);
	border-radius: 16px;
	overflow: hidden;
	box-shadow: var(--card-shadow);
	backdrop-filter: blur(16px);
	-webkit-backdrop-filter: blur(16px);
	transition: all 0.25s ease;
	min-width: 0;
	width: 100%;
	box-sizing: border-box;
}

.table-card-header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 14px 18px;
	border-bottom: 1px solid var(--border-table);
	flex-shrink: 0;
}

.table-card-title-group {
	display: flex;
	align-items: center;
	gap: 8px;
	min-width: 0;
}

.table-card-title {
	font-size: 11px;
	font-weight: 800;
	letter-spacing: 0.08em;
	text-transform: uppercase;
	color: var(--text-muted);
	margin: 0;
	white-space: nowrap;
}

.table-count-badge {
	font-size: 10px;
	font-weight: 700;
	padding: 2px 7px;
	border-radius: 9999px;
	background: var(--border-row);
	color: var(--text-muted);
	white-space: nowrap;
}

.table-responsive-box {
	overflow-x: auto;
	width: 100%;
	scrollbar-width: thin;
	scrollbar-color: rgba(16, 185, 129, 0.3) transparent;
}

.table-responsive-box::-webkit-scrollbar {
	height: 5px;
}

.table-responsive-box::-webkit-scrollbar-track {
	background: transparent;
}

.table-responsive-box::-webkit-scrollbar-thumb {
	background: rgba(16, 185, 129, 0.28);
	border-radius: 9999px;
}

.table-responsive-box::-webkit-scrollbar-thumb:hover {
	background: rgba(16, 185, 129, 0.5);
}

.minimal-table {
	width: 100%;
	border-collapse: collapse;
	text-align: left;
	font-size: 12px;
	min-width: 580px;
}

.minimal-table thead th {
	background-color: var(--th-bg);
	color: var(--text-muted);
	font-size: 10.5px;
	font-weight: 700;
	letter-spacing: 0.02em;
	padding: 10px 14px;
	border-bottom: 1px solid var(--border-table);
	white-space: nowrap;
}

.th-num {
	width: 28px;
	text-align: center;
	padding-left: 10px !important;
	padding-right: 4px !important;
}

.minimal-table tbody tr {
	border-bottom: 1px solid var(--border-row);
	transition: background-color 0.15s ease;
}

.minimal-table tbody tr:last-child {
	border-bottom: none;
}

.minimal-table tbody tr:hover {
	background-color: var(--row-hover);
}

.minimal-table td {
	padding: 10px 14px;
	vertical-align: middle;
	color: var(--text-body);
}

.td-num {
	text-align: center;
	font-size: 10.5px;
	color: var(--text-subtle);
	font-weight: 600;
	padding-left: 10px !important;
	padding-right: 4px !important;
}

/* CODE & BARCODE */
.code-barcode-wrap {
	display: flex;
	flex-direction: column;
	gap: 1px;
}

.text-code {
	font-size: 12px;
	color: #10b981;
}

.text-barcode {
	font-size: 10px;
	color: var(--text-muted);
}

/* PRODUCT CELL */
.product-cell {
	display: flex;
	align-items: center;
	gap: 12px;
}

.product-thumb {
	width: 36px;
	height: 36px;
	border-radius: 8px;
	background-color: var(--bg-page);
	border: 1px solid var(--border-input);
	display: flex;
	align-items: center;
	justify-content: center;
	overflow: hidden;
	flex-shrink: 0;
}

.product-img {
	width: 100%;
	height: 100%;
	object-fit: contain;
	padding: 3px;
}

.product-info {
	display: flex;
	flex-direction: column;
	gap: 1px;
	min-width: 0;
	max-width: 340px;
}

.product-name {
	font-size: 12.5px;
	font-weight: 700;
	color: var(--text-heading);
	line-height: 1.25;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}

.product-desc {
	font-size: 10.5px;
	color: var(--text-muted);
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}

/* CATEGORY PILL */
.category-pill {
	display: inline-flex;
	padding: 2px 8px;
	border-radius: 6px;
	font-size: 11px;
	font-weight: 600;
	background-color: var(--border-row);
	color: var(--text-muted);
}

/* STATUS PILL */
.status-pill {
	display: inline-flex;
	align-items: center;
	padding: 3px 10px;
	border-radius: 9999px;
	font-size: 11px;
	font-weight: 700;
	line-height: 1.2;
	white-space: nowrap;
}

.pill-green {
	background-color: var(--chip-green-bg);
	color: var(--chip-green-text);
}

.pill-amber {
	background-color: var(--chip-amber-bg);
	color: var(--chip-amber-text);
}

.pill-red {
	background-color: var(--chip-red-bg);
	color: var(--chip-red-text);
}

.limit-badge {
	font-size: 11px;
	color: var(--text-muted);
	font-weight: 600;
}

/* ACTION BUTTON */
.btn-row-action {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	padding: 5px 12px;
	border-radius: 8px;
	font-size: 12px;
	font-weight: 700;
	border: none;
	cursor: pointer;
	background: var(--border-row);
	color: var(--text-muted);
	transition: all 0.15s ease;
}

.btn-row-action.action-active {
	background: rgba(16, 185, 129, 0.15);
	color: #10b981;
}

.btn-row-action.action-active:hover {
	background: #10b981;
	color: #ffffff;
}

.btn-row-action:disabled {
	opacity: 0.5;
	cursor: not-allowed;
}

.empty-table-cell {
	text-align: center;
	padding: 36px 16px;
	color: var(--text-muted);
	font-size: 12px;
}

.loading-state {
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 10px;
}

.spinner {
	width: 16px;
	height: 16px;
	border: 2px solid #10b981;
	border-top-color: transparent;
	border-radius: 50%;
	animation: spin 0.8s linear infinite;
}

@keyframes spin {
	to { transform: rotate(360deg); }
}

.text-right {
	text-align: right;
}

/* ========================================================================== */
/* MODALS (CAMERA & REQUEST)                                                  */
/* ========================================================================== */
.modal-backdrop {
	position: fixed;
	inset: 0;
	background-color: var(--modal-backdrop);
	backdrop-filter: blur(4px);
	display: flex;
	align-items: center;
	justify-content: center;
	padding: 16px;
	z-index: 50;
}

.modal-card-box {
	background-color: var(--modal-bg);
	border: 1px solid var(--border-card);
	border-radius: 16px;
	width: 100%;
	max-width: 520px;
	box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
	overflow: hidden;
	animation: modalIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.camera-card {
	max-width: 600px;
}

@keyframes modalIn {
	from { opacity: 0; transform: scale(0.96) translateY(10px); }
	to { opacity: 1; transform: scale(1) translateY(0); }
}

.modal-box-header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 16px 20px;
	border-bottom: 1px solid var(--border-table);
}

.title-with-icon {
	display: flex;
	align-items: center;
	gap: 10px;
}

.modal-title {
	font-size: 15px;
	font-weight: 800;
	color: var(--text-heading);
	margin: 0;
}

.btn-close-modal {
	background: none;
	border: none;
	color: var(--text-muted);
	font-size: 16px;
	cursor: pointer;
	padding: 4px;
	border-radius: 6px;
	transition: color 0.15s ease;
}

.btn-close-modal:hover {
	color: var(--text-heading);
}

/* MODAL PRODUCT HEADER */
.modal-product-header {
	display: flex;
	align-items: center;
	gap: 14px;
	padding: 16px 20px;
	border-bottom: 1px solid var(--border-row);
}

.modal-img-wrapper {
	width: 50px;
	height: 50px;
	border-radius: 10px;
	background-color: var(--bg-page);
	border: 1px solid var(--border-input);
	display: flex;
	align-items: center;
	justify-content: center;
	overflow: hidden;
	flex-shrink: 0;
}

.modal-img-thumb {
	width: 100%;
	height: 100%;
	object-fit: contain;
	padding: 4px;
}

.barcode-tag {
	font-size: 10px;
	color: #10b981;
}

.modal-item-title {
	font-size: 16px;
	font-weight: 800;
	color: var(--text-heading);
	margin: 2px 0 0;
}

.category-text {
	font-size: 11px;
	color: var(--text-muted);
	margin: 2px 0 0;
}

/* DIVISION RULE ALERT */
.division-rule-alert {
	display: flex;
	align-items: flex-start;
	gap: 10px;
	padding: 12px 16px;
	margin: 16px 20px 0;
	border-radius: 10px;
	background-color: var(--chip-amber-bg);
	border: 1px solid rgba(245, 158, 11, 0.3);
	color: var(--chip-amber-text);
	font-size: 12px;
}

.division-rule-alert p {
	margin: 2px 0 0;
}

/* REQUEST FORM */
.request-form {
	padding: 16px 20px 20px;
	display: flex;
	flex-direction: column;
	gap: 14px;
}

.form-grid-two {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 12px;
}

.form-label {
	display: flex;
	flex-direction: column;
	gap: 6px;
	font-size: 12px;
	font-weight: 600;
	color: var(--text-body);
}

.input-readonly {
	background-color: var(--bg-page);
	border: 1px solid var(--border-input);
	color: var(--text-muted);
	padding: 8px 12px;
	border-radius: 8px;
	font-size: 12px;
	outline: none;
}

.input-qty {
	background-color: var(--bg-input);
	border: 1px solid var(--border-input);
	color: var(--text-heading);
	padding: 8px 12px;
	border-radius: 8px;
	font-size: 14px;
	font-weight: 700;
	outline: none;
	transition: border-color 0.15s ease;
}

.input-qty:focus {
	border-color: #10b981;
}

.textarea-note {
	background-color: var(--bg-input);
	border: 1px solid var(--border-input);
	color: var(--text-heading);
	padding: 8px 12px;
	border-radius: 8px;
	font-size: 12px;
	resize: vertical;
	outline: none;
	transition: border-color 0.15s ease;
}

.textarea-note:focus {
	border-color: #10b981;
}

.modal-product-title-wrap {
	flex: 1;
	min-width: 0;
}

/* CART EXIST ALERT IN POPUP */
.cart-exist-alert {
	display: flex;
	align-items: flex-start;
	gap: 10px;
	padding: 10px 14px;
	margin: 10px 20px 0;
	border-radius: 10px;
	background-color: rgba(59, 130, 246, 0.15);
	border: 1px solid rgba(59, 130, 246, 0.35);
	color: #60a5fa;
	font-size: 11px;
}

.cart-exist-alert p {
	margin: 2px 0 0;
	color: var(--text-muted);
}

.exist-icon {
	font-size: 15px;
	flex-shrink: 0;
}

/* STEPPER INPUT GROUP */
.qty-stepper-input-group {
	display: flex;
	align-items: center;
	gap: 8px;
}

.btn-qty-step {
	width: 38px;
	height: 38px;
	border-radius: 8px;
	background: var(--bg-card);
	border: 1px solid var(--border-input);
	color: var(--text-heading);
	font-size: 18px;
	font-weight: 700;
	cursor: pointer;
	display: flex;
	align-items: center;
	justify-content: center;
	transition: all 0.15s ease;
}

.btn-qty-step:hover:not(:disabled) {
	background: #10b981;
	color: #ffffff;
	border-color: #10b981;
}

.btn-qty-step:disabled {
	opacity: 0.4;
	cursor: not-allowed;
}

.input-qty-stepped {
	width: 80px;
	height: 38px;
	background-color: var(--bg-input);
	border: 1px solid var(--border-input);
	color: var(--text-heading);
	padding: 0 10px;
	border-radius: 8px;
	font-size: 16px;
	font-weight: 800;
	text-align: center;
	outline: none;
	transition: border-color 0.15s ease;
}

.input-qty-stepped:focus {
	border-color: #10b981;
}

.qty-unit-label {
	font-size: 12px;
	font-weight: 600;
	color: var(--text-muted);
}

.action-help-text {
	display: flex;
	align-items: center;
	gap: 6px;
	font-size: 11px;
	color: var(--text-subtle);
	margin-top: -4px;
}

.help-dot {
	color: #10b981;
	font-weight: bold;
}

/* 3 TOMBOL KONFIRMASI SESUAI PERMINTAAN USER: BATAL, MASUKKAN KE KERANJANG, SELESAI */
.modal-actions-cart-group {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 10px;
	margin-top: 10px;
	padding-top: 12px;
	border-top: 1px solid var(--border-row);
	flex-wrap: wrap;
}

.modal-actions-right {
	display: flex;
	align-items: center;
	gap: 8px;
	flex-wrap: wrap;
}

.btn-modal-cancel {
	padding: 9px 15px;
	border-radius: 8px;
	font-size: 12px;
	font-weight: 600;
	background-color: var(--border-row);
	border: 1px solid var(--border-input);
	color: var(--text-muted);
	cursor: pointer;
	transition: all 0.15s ease;
}

.btn-modal-cancel:hover:not(:disabled) {
	background-color: rgba(239, 68, 68, 0.15);
	color: #ef4444;
	border-color: rgba(239, 68, 68, 0.3);
}

.btn-modal-add-cart {
	display: inline-flex;
	align-items: center;
	padding: 9px 15px;
	border-radius: 8px;
	font-size: 12px;
	font-weight: 700;
	background: rgba(16, 185, 129, 0.15);
	border: 1px solid rgba(16, 185, 129, 0.4);
	color: #10b981;
	cursor: pointer;
	transition: all 0.15s ease;
}

.btn-modal-add-cart:hover:not(:disabled) {
	background: #10b981;
	color: #ffffff;
	transform: translateY(-1px);
	box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-modal-finish {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	padding: 9px 18px;
	border-radius: 8px;
	font-size: 12px;
	font-weight: 800;
	background: linear-gradient(135deg, #10b981, #059669);
	border: none;
	color: #ffffff;
	cursor: pointer;
	box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);
	transition: all 0.15s ease;
}

.btn-modal-finish:hover:not(:disabled) {
	background: linear-gradient(135deg, #059669, #047857);
	transform: translateY(-1px);
	box-shadow: 0 6px 16px rgba(16, 185, 129, 0.45);
}

.badge-in-btn {
	background: rgba(255, 255, 255, 0.25);
	padding: 1px 6px;
	border-radius: 9999px;
	font-size: 10px;
}

/* ========================================================================== */
/* MODAL KERANJANG BELANJA (CART DRAWER/MODAL)                                */
/* ========================================================================== */
.cart-modal-card {
	max-width: 600px;
	width: 100%;
	max-height: 86vh;
	display: flex;
	flex-direction: column;
	border-radius: 20px;
	overflow: hidden;
	box-sizing: border-box;
}

.cart-modal-icon-badge {
	width: 36px;
	height: 36px;
	border-radius: 10px;
	background: rgba(16, 185, 129, 0.15);
	border: 1px solid rgba(16, 185, 129, 0.3);
	display: flex;
	align-items: center;
	justify-content: center;
	font-size: 18px;
	flex-shrink: 0;
}

.cart-modal-body {
	padding: 16px 20px;
	overflow-y: auto;
	flex: 1;
	display: flex;
	flex-direction: column;
	gap: 14px;
	scrollbar-width: thin;
	scrollbar-color: rgba(16, 185, 129, 0.3) transparent;
}

.cart-modal-body::-webkit-scrollbar {
	width: 5px;
}

.cart-modal-body::-webkit-scrollbar-thumb {
	background: rgba(16, 185, 129, 0.3);
	border-radius: 9999px;
}

.cart-info-banner {
	display: flex;
	align-items: center;
	gap: 10px;
	padding: 10px 14px;
	border-radius: 10px;
	background: rgba(16, 185, 129, 0.1);
	border: 1px solid rgba(16, 185, 129, 0.25);
	color: var(--text-heading);
	font-size: 11px;
	box-sizing: border-box;
}

.cart-info-banner p {
	margin: 0;
	line-height: 1.4;
}

.info-badge-icon {
	font-size: 16px;
	flex-shrink: 0;
}

/* CART EMPTY STATE */
.cart-empty-state {
	padding: 40px 20px;
	text-align: center;
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
}

.cart-empty-icon {
	font-size: 48px;
	margin-bottom: 12px;
	opacity: 0.7;
}

.cart-empty-title {
	font-size: 16px;
	font-weight: 700;
	color: var(--text-heading);
	margin: 0 0 6px;
}

.cart-empty-desc {
	font-size: 12px;
	color: var(--text-muted);
	max-width: 320px;
	margin: 0 0 18px;
}

.btn-continue-shopping {
	padding: 9px 18px;
	border-radius: 10px;
	font-size: 12px;
	font-weight: 700;
	background: #10b981;
	color: #ffffff;
	border: none;
	cursor: pointer;
	transition: all 0.15s ease;
}

.btn-continue-shopping:hover {
	background: #059669;
}

/* CART ITEMS LIST */
.cart-items-scroll-list {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.cart-item-row {
	display: flex;
	align-items: flex-start;
	gap: 12px;
	padding: 12px;
	border-radius: 12px;
	background: var(--bg-card);
	border: 1px solid var(--border-card);
	transition: all 0.15s ease;
	min-width: 0;
	box-sizing: border-box;
}

.cart-item-row:hover {
	border-color: rgba(16, 185, 129, 0.4);
}

.cart-item-img {
	width: 48px;
	height: 48px;
	border-radius: 8px;
	object-fit: contain;
	background: var(--bg-page);
	border: 1px solid var(--border-input);
	padding: 4px;
	flex-shrink: 0;
}

.cart-item-info {
	flex: 1;
	min-width: 0;
	display: flex;
	flex-direction: column;
	gap: 4px;
}

.cart-item-header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 8px;
	min-width: 0;
}

.cart-item-name {
	font-size: 13px;
	font-weight: 700;
	color: var(--text-heading);
	line-height: 1.25;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	max-width: 380px;
}

.btn-remove-cart-item {
	background: transparent;
	border: none;
	cursor: pointer;
	font-size: 14px;
	padding: 2px 6px;
	border-radius: 6px;
	opacity: 0.6;
	transition: all 0.15s ease;
}

.btn-remove-cart-item:hover {
	opacity: 1;
	background: rgba(239, 68, 68, 0.15);
}

.cart-item-code {
	font-size: 11px;
	color: var(--text-subtle);
}

.cart-item-qty-row {
	display: flex;
	align-items: center;
	gap: 10px;
	margin-top: 4px;
}

.qty-stepper {
	display: inline-flex;
	align-items: center;
	border-radius: 8px;
	background: var(--bg-input);
	border: 1px solid var(--border-input);
	overflow: hidden;
}

.btn-step {
	width: 28px;
	height: 28px;
	display: flex;
	align-items: center;
	justify-content: center;
	background: transparent;
	border: none;
	color: var(--text-heading);
	font-weight: bold;
	font-size: 14px;
	cursor: pointer;
	transition: background 0.15s ease;
}

.btn-step:hover:not(:disabled) {
	background: #10b981;
	color: #ffffff;
}

.btn-step:disabled {
	opacity: 0.3;
	cursor: not-allowed;
}

.qty-number {
	min-width: 32px;
	text-align: center;
	font-size: 13px;
	font-weight: 700;
	color: var(--text-heading);
}

.qty-unit {
	font-size: 11px;
	color: var(--text-muted);
}

.cart-item-note-wrap {
	margin-top: 4px;
}

.cart-note-input {
	width: 100%;
	background: var(--bg-input);
	border: 1px solid var(--border-input);
	border-radius: 6px;
	padding: 5px 8px;
	font-size: 11px;
	color: var(--text-body);
	outline: none;
	transition: border-color 0.15s ease;
}

.cart-note-input:focus {
	border-color: #10b981;
}

/* CART MODAL FOOTER */
.cart-modal-footer {
	padding: 14px 20px 18px;
	border-top: 1px solid var(--border-row);
	background: var(--bg-card);
	border-radius: 0 0 20px 20px;
	display: flex;
	flex-direction: column;
	gap: 12px;
	box-sizing: border-box;
	width: 100%;
}

.cart-summary-line {
	display: flex;
	align-items: center;
	justify-content: space-between;
}

.summary-label {
	font-size: 12px;
	font-weight: 600;
	color: var(--text-muted);
}

.summary-val {
	font-size: 14px;
	font-weight: 800;
	color: #10b981;
}

.cart-footer-buttons {
	display: flex;
	align-items: center;
	justify-content: flex-end;
	gap: 8px;
	flex-wrap: wrap;
	width: 100%;
}

.btn-clear-cart {
	padding: 8px 12px;
	border-radius: 8px;
	font-size: 11px;
	font-weight: 600;
	background: var(--border-row);
	border: 1px solid var(--border-input);
	color: var(--text-muted);
	cursor: pointer;
	transition: all 0.15s ease;
	white-space: nowrap;
}

.btn-clear-cart:hover:not(:disabled) {
	background: rgba(239, 68, 68, 0.15);
	color: #ef4444;
	border-color: rgba(239, 68, 68, 0.3);
}

.btn-continue-picking {
	padding: 8px 14px;
	border-radius: 8px;
	font-size: 12px;
	font-weight: 600;
	background: var(--bg-input);
	border: 1px solid var(--border-card);
	color: var(--text-heading);
	cursor: pointer;
	transition: all 0.15s ease;
	white-space: nowrap;
}

.btn-continue-picking:hover:not(:disabled) {
	border-color: #10b981;
	color: #10b981;
}

.btn-checkout-cart {
	padding: 9px 18px;
	border-radius: 8px;
	font-size: 12px;
	font-weight: 800;
	background: linear-gradient(135deg, #10b981, #059669);
	border: none;
	color: #ffffff;
	cursor: pointer;
	box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
	transition: all 0.15s ease;
	white-space: nowrap;
}

.btn-checkout-cart:hover:not(:disabled) {
	background: linear-gradient(135deg, #059669, #047857);
	transform: translateY(-1px);
	box-shadow: 0 6px 18px rgba(16, 185, 129, 0.45);
}

/* ========================================================================== */
/* FLOATING CART BAR (BOTTOM RIGHT STICKY)                                    */
/* ========================================================================== */
.floating-cart-badge-bar {
	position: fixed;
	bottom: 24px;
	right: 24px;
	z-index: 40;
	animation: slideUp 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

@keyframes slideUp {
	0% { transform: translateY(50px); opacity: 0; }
	100% { transform: translateY(0); opacity: 1; }
}

.floating-cart-btn {
	display: flex;
	align-items: center;
	gap: 12px;
	padding: 10px 18px 10px 12px;
	border-radius: 9999px;
	background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
	border: 1.5px solid #10b981;
	box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5), 0 0 20px rgba(16, 185, 129, 0.3);
	color: #ffffff;
	cursor: pointer;
	transition: all 0.2s ease;
}

.floating-cart-btn:hover {
	transform: scale(1.03) translateY(-2px);
	box-shadow: 0 12px 35px rgba(0, 0, 0, 0.6), 0 0 25px rgba(16, 185, 129, 0.45);
}

.floating-cart-icon-circle {
	position: relative;
	width: 36px;
	height: 36px;
	border-radius: 9999px;
	background: #10b981;
	display: flex;
	align-items: center;
	justify-content: center;
	font-size: 18px;
}

.floating-count {
	position: absolute;
	top: -4px;
	right: -4px;
	background: #ef4444;
	color: #ffffff;
	font-size: 10px;
	font-weight: 900;
	width: 17px;
	height: 17px;
	border-radius: 9999px;
	display: flex;
	align-items: center;
	justify-content: center;
	border: 1.5px solid #0f172a;
}

.floating-cart-texts {
	display: flex;
	flex-direction: column;
	text-align: left;
}

.floating-title {
	font-size: 12px;
	font-weight: 800;
	color: #ffffff;
}

.floating-subtitle {
	font-size: 10px;
	color: #94a3b8;
}

.floating-arrow {
	font-size: 11px;
	font-weight: 700;
	color: #34d399;
	background: rgba(16, 185, 129, 0.15);
	padding: 4px 10px;
	border-radius: 9999px;
}

/* ========================================================================== */
/* FLOATING TOAST NOTIFICATION                                                */
/* ========================================================================== */
.cart-floating-toast {
	position: fixed;
	top: 24px;
	right: 24px;
	z-index: 100;
	display: flex;
	align-items: center;
	gap: 10px;
	padding: 12px 18px;
	border-radius: 12px;
	background: rgba(15, 23, 42, 0.95);
	border: 1px solid #10b981;
	box-shadow: 0 10px 35px rgba(0, 0, 0, 0.5), 0 0 15px rgba(16, 185, 129, 0.25);
	color: #ffffff;
	font-size: 12px;
	backdrop-filter: blur(8px);
	animation: slideDownToast 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

@keyframes slideDownToast {
	0% { transform: translateY(-30px); opacity: 0; }
	100% { transform: translateY(0); opacity: 1; }
}

.toast-icon {
	color: #10b981;
	font-weight: 900;
	font-size: 14px;
}

.toast-text {
	font-weight: 600;
	max-width: 320px;
}

.toast-btn-cart {
	padding: 4px 10px;
	border-radius: 6px;
	background: rgba(16, 185, 129, 0.2);
	border: 1px solid rgba(16, 185, 129, 0.4);
	color: #34d399;
	font-size: 11px;
	font-weight: 700;
	cursor: pointer;
	white-space: nowrap;
	transition: all 0.15s ease;
}

.toast-btn-cart:hover {
	background: #10b981;
	color: #ffffff;
}

.toast-close {
	background: transparent;
	border: none;
	color: #94a3b8;
	cursor: pointer;
	font-size: 12px;
	padding: 2px 4px;
}

.toast-close:hover {
	color: #ffffff;
}



.camera-error-banner {
	padding: 10px 16px;
	background-color: var(--chip-red-bg);
	color: var(--chip-red-text);
	font-size: 12px;
	border-bottom: 1px solid rgba(239, 68, 68, 0.2);
}



/* ========================================================================== */
/* BARCODE BADGE & VIEWER MODAL STYLES                                        */
/* ========================================================================== */
.barcode-sub-row {
	display: flex;
	align-items: center;
	gap: 6px;
	margin-top: 2px;
}

.btn-barcode-icon {
	display: inline-flex;
	align-items: center;
	gap: 3px;
	padding: 2px 7px;
	border-radius: 6px;
	font-size: 10px;
	font-weight: 700;
	color: #10b981;
	background: rgba(16, 185, 129, 0.12);
	border: 1px solid rgba(16, 185, 129, 0.3);
	cursor: pointer;
	transition: all 0.15s ease;
}

.btn-barcode-icon:hover {
	background: #10b981;
	color: #ffffff;
	transform: translateY(-1px);
	box-shadow: 0 2px 8px rgba(16, 185, 129, 0.35);
}

.barcode-modal-card {
	max-width: 480px;
	width: 100%;
}

.barcode-preview-body {
	padding: 20px;
	display: flex;
	flex-direction: column;
	gap: 18px;
}

.barcode-ticket-card {
	background: #ffffff;
	color: #0f172a;
	border: 2px dashed #94a3b8;
	border-radius: 14px;
	padding: 20px;
	text-align: center;
	box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
}

.ticket-header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	border-bottom: 1px solid #e2e8f0;
	padding-bottom: 8px;
	margin-bottom: 12px;
}

.ticket-brand {
	font-size: 11px;
	font-weight: 800;
	letter-spacing: 1px;
	color: #059669;
}

.ticket-category {
	font-size: 10px;
	font-weight: 700;
	background: #f1f5f9;
	color: #475569;
	padding: 2px 8px;
	border-radius: 9999px;
}

.ticket-product-row {
	display: flex;
	align-items: center;
	gap: 12px;
	text-align: left;
	margin-bottom: 14px;
}

.ticket-thumb {
	width: 52px;
	height: 52px;
	border-radius: 8px;
	object-fit: cover;
	background: #f8fafc;
	border: 1px solid #e2e8f0;
	flex-shrink: 0;
}

.ticket-product-info {
	flex: 1;
	min-width: 0;
}

.ticket-name {
	font-size: 13px;
	font-weight: 800;
	color: #0f172a;
	margin: 0 0 4px;
	line-height: 1.3;
}

.ticket-meta-grid {
	display: flex;
	flex-wrap: wrap;
	gap: 10px;
	font-size: 11px;
	color: #64748b;
}

.ticket-meta-grid b {
	color: #0f172a;
}

.ticket-qr-main-box {
	display: flex;
	flex-direction: column;
	align-items: center;
	padding: 16px 12px;
	background: #ffffff;
	border-radius: 12px;
	border: 1px solid #e2e8f0;
	margin-bottom: 12px;
}

.ticket-qr-large-img {
	width: 170px;
	height: 170px;
	display: block;
}

.ticket-qr-badge {
	margin-top: 8px;
	font-size: 11px;
	font-weight: 800;
	color: #059669;
	letter-spacing: 1px;
	background: #ecfdf5;
	padding: 3px 12px;
	border-radius: 9999px;
}

.ticket-qr-hint {
	font-size: 10px;
	color: #94a3b8;
	margin-top: 4px;
}

.barcode-modal-actions {
	display: flex;
	flex-direction: column;
	gap: 8px;
}

.btn-print-barcode {
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
	padding: 12px 18px;
	background: linear-gradient(135deg, #10b981 0%, #059669 100%);
	color: #ffffff;
	border: none;
	border-radius: 10px;
	font-size: 13px;
	font-weight: 700;
	cursor: pointer;
	box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
	transition: all 0.2s ease;
}

.btn-print-barcode:hover {
	transform: translateY(-1px);
	box-shadow: 0 6px 18px rgba(16, 185, 129, 0.45);
}

.btn-download-barcode {
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 6px;
	padding: 10px 16px;
	background: var(--bg-card);
	color: var(--text-heading);
	border: 1px solid var(--border-card);
	border-radius: 10px;
	font-size: 12px;
	font-weight: 600;
	text-decoration: none;
	transition: all 0.15s ease;
}

.btn-download-barcode:hover {
	border-color: #10b981;
	color: #10b981;
}

.btn-quick-request {
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 6px;
	padding: 10px 16px;
	background: rgba(16, 185, 129, 0.15);
	color: #10b981;
	border: 1px solid rgba(16, 185, 129, 0.35);
	border-radius: 10px;
	font-size: 12px;
	font-weight: 700;
	cursor: pointer;
	transition: all 0.15s ease;
}

.btn-quick-request:hover {
	background: #10b981;
	color: #ffffff;
}

/* ========================================================================== */
/* CAMERA SCANNER LAYOUT: RESPONSIF & KOTAK MENYESUAIKAN KAMERA               */
/* ========================================================================== */
.camera-card {
	max-width: 480px;
	width: 92vw;
	border-radius: 20px;
	overflow: hidden;
	background: #0f172a;
	box-shadow: 0 25px 60px rgba(0, 0, 0, 0.75);
}

.camera-viewfinder-box {
	position: relative;
	width: 100%;
	aspect-ratio: 4 / 3;
	background: #000000;
	overflow: hidden;
}

.html5-qr-container {
	position: absolute;
	inset: 0;
	width: 100%;
	height: 100%;
	background: #000000;
}

#atk-qr-reader {
	position: absolute !important;
	inset: 0 !important;
	width: 100% !important;
	height: 100% !important;
	border: none !important;
	background: #000000 !important;
	padding: 0 !important;
	margin: 0 !important;
	overflow: hidden !important;
}

#atk-qr-reader video,
.camera-video-feed {
	position: absolute !important;
	inset: 0 !important;
	width: 100% !important;
	height: 100% !important;
	min-width: 100% !important;
	min-height: 100% !important;
	object-fit: cover !important;
	object-position: center !important;
	display: block !important;
	transform: none !important;
	-webkit-transform: none !important;
}

#atk-qr-reader__scan_region {
	position: absolute !important;
	inset: 0 !important;
	width: 100% !important;
	height: 100% !important;
	background: transparent !important;
	display: flex !important;
	align-items: center !important;
	justify-content: center !important;
	overflow: hidden !important;
}

#atk-qr-reader__scan_region img,
#atk-qr-reader__dashboard,
#atk-qr-reader__header_message {
	display: none !important;
}

/* OVERLAY KOTAK BIDIK YANG PRESISI DI TENGAH SESUAI KAMERA */
.scanner-custom-overlay {
	position: absolute;
	inset: 0;
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	pointer-events: none;
	z-index: 10;
}

.scanner-target-box {
	position: relative;
	width: 250px;
	height: 250px;
	max-width: 72%;
	max-height: 72%;
	border: 2px dashed rgba(16, 185, 129, 0.45);
	border-radius: 20px;
	box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.42);
}

.corner {
	position: absolute;
	width: 28px;
	height: 28px;
	border-color: #10b981;
	border-style: solid;
}

.top-left { top: -2px; left: -2px; border-width: 4px 0 0 4px; border-top-left-radius: 12px; }
.top-right { top: -2px; right: -2px; border-width: 4px 4px 0 0; border-top-right-radius: 12px; }
.bottom-left { bottom: -2px; left: -2px; border-width: 0 0 4px 4px; border-bottom-left-radius: 12px; }
.bottom-right { bottom: -2px; right: -2px; border-width: 0 4px 4px 0; border-bottom-right-radius: 12px; }

.scanner-laser-line {
	position: absolute;
	left: 6px;
	right: 6px;
	height: 2.5px;
	background: linear-gradient(90deg, transparent 0%, #10b981 50%, transparent 100%);
	box-shadow: 0 0 10px #10b981, 0 0 20px rgba(16, 185, 129, 0.6);
	animation: laserSweep 2s ease-in-out infinite alternate;
}

@keyframes laserSweep {
	0% { top: 12px; opacity: 0.85; }
	100% { top: calc(100% - 14px); opacity: 0.85; }
}

.scanner-guide-text {
	margin-top: 14px;
	background: rgba(15, 23, 42, 0.75);
	backdrop-filter: blur(6px);
	border: 1px solid rgba(255, 255, 255, 0.1);
	color: #e2e8f0;
	font-size: 11px;
	font-weight: 600;
	padding: 5px 14px;
	border-radius: 9999px;
	letter-spacing: 0.3px;
	text-shadow: 0 1px 2px rgba(0, 0, 0, 0.8);
}
</style>

<!-- GLOBAL UNSCOPED STYLE UNTUK ELEMEN YANG DIINJEKSIKAN DINAMIS OLEH HTML5-QRCODE -->
<style>
#atk-qr-reader {
	position: absolute !important;
	inset: 0 !important;
	width: 100% !important;
	height: 100% !important;
	border: none !important;
	background: #000000 !important;
	padding: 0 !important;
	margin: 0 !important;
	overflow: hidden !important;
}

#atk-qr-reader video {
	position: absolute !important;
	inset: 0 !important;
	width: 100% !important;
	height: 100% !important;
	min-width: 100% !important;
	min-height: 100% !important;
	object-fit: cover !important;
	object-position: center !important;
	display: block !important;
	transform: none !important;
	-webkit-transform: none !important;
}

#atk-qr-reader__scan_region {
	position: absolute !important;
	inset: 0 !important;
	width: 100% !important;
	height: 100% !important;
	background: transparent !important;
	overflow: hidden !important;
}

#atk-qr-reader__scan_region img,
#atk-qr-reader__dashboard,
#atk-qr-reader__header_message {
	display: none !important;
}
</style>

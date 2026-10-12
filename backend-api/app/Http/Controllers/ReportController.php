<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\CleaningRequest;
use App\Models\Product;
use App\Models\RoomBooking;
use App\Models\VehicleBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ReportController extends Controller
{
    /**
     * Ambil laporan aktivitas seluruh user (ATK, Ruangan, Mobil, Cleaning Service)
     * Dapat difilter berdasarkan rentang tanggal, divisi, tahun, dan pencarian.
     */
    public function all(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $search = $request->query('search');
        $divisionId = $request->query('division_id');
        $year = $request->query('year');

        return response()->json([
            'atk'          => $this->getAtkReports($startDate, $endDate, $search, $divisionId, $year),
            'atk_products' => Product::where('status', '!=', 'archived')->orderBy('stock', 'asc')->get(['id', 'name', 'stock', 'unit', 'image', 'status']),
            'rooms'        => $this->getRoomReports($startDate, $endDate, $search, $divisionId, $year),
            'vehicles'     => $this->getVehicleReports($startDate, $endDate, $search, $divisionId, $year),
            'cleaning'     => $this->getCleaningReports($startDate, $endDate, $search, $divisionId, $year),
        ]);
    }

    /**
     * Laporan Peminjaman ATK
     */
    private function getAtkReports(?string $startDate, ?string $endDate, ?string $search, $divisionId = null, $year = null): Collection
    {
        $query = Borrowing::with(['user.division', 'items.product'])->latest();

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        if ($divisionId) {
            $query->where(function ($q) use ($divisionId) {
                $q->where('division_id', $divisionId)
                  ->orWhereHas('user', fn($uq) => $uq->where('division_id', $divisionId));
            });
        }
        if ($year) {
            $query->whereYear('created_at', $year);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                       ->orWhere('nip', 'like', "%{$search}%");
                })->orWhere('status', 'like', "%{$search}%");
            });
        }

        return $query->get()->map(function ($b) {
            $firstProduct = $b->items->first()?->product;
            return [
                'id'            => $b->id,
                'user_name'     => $b->user?->name ?? 'User',
                'user_nip'      => $b->user?->nip ?? '-',
                'division'      => $b->user?->division?->name ?? ($b->division?->name ?? '-'),
                'division_id'   => $b->user?->division_id ?? $b->division_id,
                'items_count'   => $b->items->count(),
                'items_summary' => $b->items->map(fn($it) => ($it->product?->name ?? 'Item') . ' (' . $it->quantity . ' ' . ($it->product?->unit ?? 'Pcs') . ')')->join(', '),
                'items'         => $b->items->map(fn($it) => [
                    'product_id'   => $it->product_id,
                    'product_name' => $it->product?->name ?? 'Barang ATK',
                    'quantity'     => (int)$it->quantity,
                    'unit'         => $it->product?->unit ?? 'Pcs',
                    'image'        => $it->product?->image ?? '/images/atk/default-atk.svg',
                    'stock'        => (int)($it->product?->stock ?? 0),
                ]),
                'product_image' => $firstProduct?->image ?? '/images/atk/default-atk.svg',
                'status'        => $b->status,
                'request_note'  => $b->request_note,
                'date'          => $b->created_at ? $b->created_at->format('Y-m-d H:i') : '-',
                'month'         => $b->created_at ? (int)$b->created_at->format('n') : 1,
                'year'          => $b->created_at ? (int)$b->created_at->format('Y') : (int)date('Y'),
            ];
        });
    }

    /**
     * Laporan Peminjaman Ruangan
     */
    private function getRoomReports(?string $startDate, ?string $endDate, ?string $search, $divisionId = null, $year = null): Collection
    {
        $query = RoomBooking::with(['user.division', 'room'])->latest();

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        if ($divisionId) {
            $query->whereHas('user', fn($uq) => $uq->where('division_id', $divisionId));
        }
        if ($year) {
            $query->whereYear('created_at', $year);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('agenda', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$search}%")->orWhere('nip', 'like', "%{$search}%"))
                  ->orWhereHas('room', fn($rq) => $rq->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%"));
            });
        }

        return $query->get()->map(function ($r) {
            return [
                'id'          => $r->id,
                'user_name'   => $r->user?->name ?? 'User',
                'user_nip'    => $r->user?->nip ?? '-',
                'division'    => $r->user?->division?->name ?? '-',
                'division_id' => $r->user?->division_id,
                'room_name'   => $r->room?->name ?? 'Ruangan',
                'room_code'   => $r->room?->code ?? '-',
                'room_image'  => $r->room?->image ?? '/images/rooms/default-room.svg',
                'agenda'      => $r->agenda,
                'start_time'  => $r->start_time ? $r->start_time->format('Y-m-d H:i') : '-',
                'end_time'    => $r->end_time ? $r->end_time->format('Y-m-d H:i') : '-',
                'status'      => $r->status,
                'notes'       => $r->notes,
                'date'        => $r->created_at ? $r->created_at->format('Y-m-d H:i') : '-',
                'month'       => $r->created_at ? (int)$r->created_at->format('n') : 1,
                'year'        => $r->created_at ? (int)$r->created_at->format('Y') : (int)date('Y'),
            ];
        });
    }

    /**
     * Laporan Peminjaman Mobil / Kendaraan Dinas
     */
    private function getVehicleReports(?string $startDate, ?string $endDate, ?string $search, $divisionId = null, $year = null): Collection
    {
        $query = VehicleBooking::with(['user.division', 'vehicle', 'driver'])->latest();

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        if ($divisionId) {
            $query->whereHas('user', fn($uq) => $uq->where('division_id', $divisionId));
        }
        if ($year) {
            $query->whereYear('created_at', $year);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('destination', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$search}%")->orWhere('nip', 'like', "%{$search}%"))
                  ->orWhereHas('vehicle', fn($vq) => $vq->where('name', 'like', "%{$search}%")->orWhere('license_plate', 'like', "%{$search}%"));
            });
        }

        return $query->get()->map(function ($v) {
            return [
                'id'               => $v->id,
                'vehicle_id'       => $v->vehicle_id,
                'driver_id'        => $v->driver_id,
                'user_name'        => $v->requester_name,
                'user_nip'         => $v->requester_nip,
                'division'         => $v->requester_division,
                'division_id'      => $v->user?->division_id,
                'borrower_name'    => $v->requester_name,
                'borrower_division'=> $v->requester_division,
                'vehicle_name'     => $v->vehicle?->name ?? 'Kendaraan',
                'license_plate'    => $v->vehicle?->license_plate ?? '-',
                'vehicle_image'    => $v->vehicle?->image ?? '/images/vehicles/default-vehicle.svg',
                'driver_name'      => $v->driver_name ?? ($v->driver?->name ?? ($v->vehicle?->current_driver_name ?? 'Menunggu Penugasan')),
                'destination'      => $v->destination,
                'purpose'          => $v->purpose,
                'passengers'       => $v->passengers ?? ($v->passengers_count . ' Orang'),
                'passengers_count' => $v->passengers_count,
                'start_date'       => $v->start_date ? $v->start_date->format('Y-m-d H:i') : '-',
                'end_date'         => $v->end_date ? $v->end_date->format('Y-m-d H:i') : '-',
                'status'           => $v->status,
                'booking_type'     => $v->booking_type ?? 'incidental',
                'is_routine'       => (bool)($v->is_routine ?? false),
                'routine_schedule' => $v->routine_schedule ?? null,
                'notes'            => $v->notes,
                'date'             => $v->created_at ? $v->created_at->format('Y-m-d H:i') : '-',
                'month'            => $v->created_at ? (int)$v->created_at->format('n') : 1,
                'year'             => $v->created_at ? (int)$v->created_at->format('Y') : (int)date('Y'),
            ];
        });
    }

    /**
     * Laporan Layanan Cleaning Service
     */
    private function getCleaningReports(?string $startDate, ?string $endDate, ?string $search, $divisionId = null, $year = null): Collection
    {
        $query = CleaningRequest::with(['user.division', 'cleaningTool'])->latest();

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        if ($divisionId) {
            $query->whereHas('user', fn($uq) => $uq->where('division_id', $divisionId));
        }
        if ($year) {
            $query->whereYear('created_at', $year);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('service_type', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$search}%")->orWhere('nip', 'like', "%{$search}%"));
            });
        }

        return $query->get()->map(function ($c) {
            return [
                'id'             => $c->id,
                'user_name'      => $c->user?->name ?? 'User',
                'user_nip'       => $c->user?->nip ?? '-',
                'division'       => $c->user?->division?->name ?? '-',
                'division_id'    => $c->user?->division_id,
                'service_type'   => $c->service_type,
                'location'       => $c->location,
                'tool_name'      => $c->cleaningTool?->name ?? '-',
                'tool_image'     => $c->cleaningTool?->image ?? '/images/cleaning/default-cleaning.svg',
                'requested_date' => $c->requested_date ? $c->requested_date->format('Y-m-d H:i') : '-',
                'status'         => $c->status,
                'date'           => $c->created_at ? $c->created_at->format('Y-m-d H:i') : '-',
                'month'          => $c->created_at ? (int)$c->created_at->format('n') : 1,
                'year'           => $c->created_at ? (int)$c->created_at->format('Y') : (int)date('Y'),
            ];
        });
    }
}

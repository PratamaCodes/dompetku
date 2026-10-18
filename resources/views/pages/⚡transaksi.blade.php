<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaksi;
use App\Models\Kategori;

new class extends Component {
    use WithPagination;

    public $no = 1;
    public $kategori_id;
    public $nama_kategori;
    public $transaksi_id;
    public $nominal;
    public $tanggal;
    public $keterangan;

    public function showByIdUpdate($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $this->transaksi_id = $transaksi->id;
        $this->nominal = $transaksi->nominal;
        $this->tanggal = $transaksi->tanggal;
        $this->keterangan = $transaksi->keterangan;

        $this->dispatch('open-modal-update');
    }

    public function showByIdDelete($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $this->transaksi_id = $transaksi->id;
        $this->tanggal = $transaksi->tanggal;
        $this->nominal = $transaksi->nominal;
        $this->nama_kategori = $transaksi->kategori->nama_kategori;

        $this->dispatch('open-modal-delete');
    }

    public function resetModel()
    {
        $this->reset();
    }

    public function store()
    {
        $validated = $this->validate([
            'kategori_id' => 'required',
            'nominal' => 'required',
            'tanggal' => 'required',
            'keterangan' => 'required'
        ]);

        Transaksi::create($validated);

        $this->redirect('/transaksi');
    }

    public function update($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $validated = $this->validate([
            'transaksi_id' => 'required',
            'nominal' => 'required',
            'tanggal' => 'required',
            'keterangan' => 'required'
        ]);

        $transaksi->update($validated);

        $this->reset();
        $this->redirect('/transaksi');
    }

    public function delete($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $transaksi->delete();

        $this->reset();
        $this->redirect('/transaksi');
    }

    public function with()
    {
        return ['kategoris' => Kategori::all(), 'transaksis' => Transaksi::paginate(5)];
    }
};
?>

<div class="flex flex-col" x-data="{ modal: null }" x-on:open-modal-update.window="modal = 'update'" x-on:open-modal-delete.window="modal = 'delete'" x-on:keydown.escape.window="modal = null">
    <div class="card bg-base-100 shadow-lg mt-10 min-h-80">
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <div>
                         <button class="btn btn-sm btn-success text-white" @click="modal = 'create'"><i class="ri-add-line"></i></button>
                    </div>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Kategori</th>
                            <th>Nominal</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                    </thead>
                    <tbody>
                        @forelse ($transaksis as $transaksi)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $transaksi->tanggal }}</td>
                                <td>{{ $transaksi->kategori->nama_kategori }}</td>
                                <td>{{ $transaksi->nominal }}</td>
                                <td>{{ $transaksi->keterangan }}</td>
                                <td class="flex gap-2">
                                    <button wire:click='showByIdUpdate({{ $transaksi->id }})' class="btn btn-sm btn-primary" ><i class="ri-pencil-fill"></i></button>
                                    <button wire:click='showByIdDelete({{ $transaksi->id }})' class="btn btn-sm btn-error text-white" ><i class="ri-delete-bin-fill"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Data tidak ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                        {{-- Modal Create --}}
                        <div class="modal" x-cloak x-show="modal === 'create'" :class="{ 'modal-open': modal === 'create' }" @click.self="modal = null">
                            <div class="modal-box">
                                <div>
                                    <span class="font-semibold">Tambah Data Transaksi</span>
                                    <button type="button" @click="modal = null" wire:click='resetModel' class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">âœ•</button>
                                </div>
                                <form wire:submit='store()' class="w-full my-3">
                                    <div class="flex flex-col mx-2">
                                        <div class="flex flex-col w-full my-2 gap-1">
                                            <label class="label" for="kategori_id">Nama Kategori</label>
                                            <select wire:model='kategori_id' name="kategori_id" id="kategori_id" class="select w-full" required>
                                                <option selected>Pilih Kategori</option>
                                            @forelse ($kategoris as $kategori)
                                                <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                            @empty
                                                <option>Tidak ada kategori</option>
                                            @endforelse
                                            </select>
                                        </div>
                                        @error('kategori_id')
                                            {{ $message }}                                            
                                        @enderror
                                        <div class="flex flex-col w-full my-2 gap-1">
                                            <label class="label" for="nominal">Nominal</label>
                                            <input type="number" name="nominal" id="nominal" class="input w-full" wire:model='nominal' required/>
                                        </div>
                                        @error('nominal')
                                            {{ $message }}                                            
                                        @enderror
                                        <div class="flex flex-col w-full my-2 gap-1">
                                            <label class="label" for="nominal">Tanggal</label>
                                            <input type="date" name="tanggal" id="tanggal" class="input w-full" wire:model='tanggal' required/>
                                        </div>
                                        @error('tanggal')
                                            {{ $message }}                                            
                                        @enderror
                                        <div class="flex flex-col w-full my-2 gap-1">
                                            <label class="label" for="nominal">Keterangan</label>
                                            <input type="text" name="keterangan" id="keterangan" class="input w-full" wire:model='keterangan' required/>
                                        </div>
                                        @error('tanggal')
                                            {{ $message }}                                            
                                        @enderror
                                        <div class="w-full my-2">
                                            <button class="btn btn-sm btn-success text-white w-full">Tambah Transaksi</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Modal Update --}}
                        <div class="modal" x-cloak x-show="modal === 'update'" :class="{ 'modal-open': modal === 'update' }" @click.self="modal = null">
                            <div class="modal-box">
                                <div>
                                    <span class="font-semibold">Edit Data Transaksi</span>
                                    <button type="button" @click="modal = null" wire:click='resetModel' class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">âœ•</button>
                                </div>
                                <form wire:submit='update({{ $transaksi_id }})' class="w-full my-3">
                                    <div class="flex flex-col mx-2">
                                        <div class="flex flex-col w-full my-2 gap-1">
                                            <label class="label" for="kategori_id">Nama Kategori</label>
                                            <select wire:model='kategori_id' name="kategori_id" id="kategori_id" class="select w-full" required>
                                            @forelse ($kategoris as $kategori)
                                                <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                            @empty
                                                <option>Tidak ada kategori</option>
                                            @endforelse
                                            </select>
                                        </div>
                                        @error('kategori_id')
                                            {{ $message }}                                            
                                        @enderror
                                        <div class="flex flex-col w-full my-2 gap-1">
                                            <label class="label" for="nominal">Nominal</label>
                                            <input type="number" name="nominal" id="nominal" class="input w-full" wire:model='nominal' required/>
                                        </div>
                                        @error('nominal')
                                            {{ $message }}                                            
                                        @enderror
                                        <div class="flex flex-col w-full my-2 gap-1">
                                            <label class="label" for="nominal">Tanggal</label>
                                            <input type="date" name="tanggal" id="tanggal" class="input w-full" wire:model='tanggal' required/>
                                        </div>
                                        @error('tanggal')
                                            {{ $message }}                                            
                                        @enderror
                                        <div class="flex flex-col w-full my-2 gap-1">
                                            <label class="label" for="nominal">Keterangan</label>
                                            <input type="text" name="keterangan" id="keterangan" class="input w-full" wire:model='keterangan' required/>
                                        </div>
                                        @error('tanggal')
                                            {{ $message }}                                            
                                        @enderror
                                        <div class="w-full my-2">
                                            <button class="btn btn-sm btn-success text-white w-full">Tambah Transaksi</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Modal Delete --}}
                        <div class="modal" x-cloak x-show="modal === 'delete'" :class="{ 'modal-open': modal === 'delete' }" @click.self="modal = null">
                            <div class="modal-box">
                                <div>
                                    <button type="button" @click="modal = null" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">âœ•</button>
                                </div>
                                <form wire:submit='delete({{ $transaksi_id }})' class="w-full my-3">
                                    <div class="flex flex-col mx-2">
                                        <div class="flex justify-center w-full">
                                           <span class="font-semibold text-center">Apakah Anda Yakin Untuk Menghapus Transaksi Ini</span>
                                        </div>
                                        <div class="flex flex-col items-center gap-3 py-3">
                                            <div class="w-16 h-16 rounded-full bg-error/10 flex items-center justify-center">
                                                <i class="ri-delete-bin-6-line text-error text-3xl"></i>
                                            </div>

                                            <div class="text-center">
                                                <h3 class="font-semibold text-lg">Hapus Data?</h3>
                                                <p class="text-sm text-gray-500">
                                                    Data berikut akan dihapus secara permanen.
                                                </p>
                                            </div>

                                            <div class="w-full rounded-lg border border-base-300 bg-base-200 p-4">
                                                <div class="flex justify-between">
                                                    <span class="font-medium">Kategori</span>
                                                    <span>{{ $nama_kategori }}</span>
                                                </div>

                                                <div class="divider my-2"></div>

                                                <div class="flex justify-between">
                                                    <span class="font-medium">Nominal</span>
                                                    <span class="font-semibold text-error">Rp {{ $nominal }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-full my-2">
                                            <button class="btn btn-sm btn-error w-full text-white">Hapus Transaksi</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                {{ $transaksis->links() }}
            </div>
        </div>
    </div>
</div>
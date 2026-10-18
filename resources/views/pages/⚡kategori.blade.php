<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Kategori;

new class extends Component {
    use WithPagination;

    public $no = 1;
    public $kategori_id;
    public $nama_kategori;
    public $tipe;

    public $tipeList = ['pemasukan','pengeluaran'];

    public function showByIdUpdate($id)
    {
        $kategori = Kategori::findOrFail($id);

        $this->kategori_id = $kategori->id;
        $this->nama_kategori = $kategori->nama_kategori;
        $this->tipe = $kategori->tipe;

        $this->dispatch('open-modal-update');
    }

    public function showByIdDelete($id)
    {
        $kategori = Kategori::findOrFail($id);

        $this->kategori_id = $kategori->id;
        $this->nama_kategori = $kategori->nama_kategori;

        $this->dispatch('open-modal-delete');
    }

    public function resetModel()
    {
        $this->reset();
    }

    public function store()
    {
        $validated = $this->validate([
            'nama_kategori' => 'required|min:3',
            'tipe' => 'required'
        ]);

        Kategori::create($validated);

        $this->redirect('/kategori');
    }

    public function update($id)
    {
        $kategori = Kategori::findOrFail($id);
        $validated = $this->validate([
            'nama_kategori' => 'required|min:3',
            'tipe' => 'required'
        ]);

        $kategori->update($validated);

        $this->reset();
        $this->redirect('/kategori');
    }

    public function delete($id)
    {
        $kategori = Kategori::findOrFail($id);

        $kategori->delete();

        $this->reset();
        $this->redirect('/kategori');
    }   

    public function with()
    {
        return ['kategoris' => Kategori::paginate(5)];
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
                            <th>Nama Kategori</th>
                            <th>Tipe</th>
                            <th>Aksi</th>
                    </thead>
                    <tbody>
                        @forelse ($kategoris as $kategori)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $kategori->nama_kategori }}</td>
                                <td>{{ $kategori->tipe }}</td>
                                <td class="flex gap-2">
                                    <button wire:click='showByIdUpdate({{ $kategori->id }})' class="btn btn-sm btn-primary" ><i class="ri-pencil-fill"></i></button>
                                    <button wire:click='showByIdDelete({{ $kategori->id }})' class="btn btn-sm btn-error text-white" ><i class="ri-delete-bin-fill"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Data tidak ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
{{-- Modal Create --}}
                        <div class="modal" x-cloak x-show="modal === 'create'" :class="{ 'modal-open': modal === 'create' }" @click.self="modal = null">
                            <div class="modal-box">
                                <div>
                                    <span class="font-semibold">Tambah Data Kategori</span>
                                    <button type="button" @click="modal = null" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">âœ•</button>
                                </div>
                                <form wire:submit='store()' class="w-full my-3">
                                    <div class="flex flex-col mx-2">
                                        <div class="flex flex-col w-full my-2 gap-1">
                                            <label class="label" for="nama_kategori">Nama Kategori</label>
                                            <input type="text" name="nama_kategori" id="nama_kategori" class="input w-full" wire:model='nama_kategori' required />
                                        </div>
                                        @error('nama_kategori')
                                            {{ $message }}                                            
                                        @enderror
                                        <div class="flex flex-col w-full my-2 gap-1">
                                            <label class="label" for="tipe">Tipe</label>
                                            <select class="select w-full" name="tipe" id="tipe" wire:model='tipe' required>
                                                <option selected>Pilih Tipe</option>
                                                @foreach ($tipeList as $tipe)
                                                    <option value="{{ $tipe }}">{{ $tipe }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('tipe')
                                            {{ $message }}                                            
                                        @enderror
                                        <div class="w-full my-2">
                                            <button class="btn btn-sm btn-success text-white w-full" wire:loading.attr="disabled">
                                                <span wire:loading class="loading loading-spinner loading-xs"></span>

                                                <span wire:loading.remove>
                                                    Tambah Kategori
                                                </span>

                                                <span wire:loading>
                                                    Menyimpan...
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Modal Update --}}
                        <div class="modal" x-cloak x-show="modal === 'update'" :class="{ 'modal-open': modal === 'update' }" @click.self="modal = null">
                            <div class="modal-box">
                                <div>
                                    <span class="font-semibold">Edit Data Kategori</span>
                                    <button type="button" @click="modal = null" wire:click='resetModel' class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">âœ•</button>
                                </div>
                                <form wire:submit='update({{ $kategori_id }})' class="w-full my-3">
                                    <div class="flex flex-col mx-2">
                                        <div class="flex flex-col w-full my-2 gap-1">
                                            <label class="label" for="nama_kategori">Nama Kategori</label>
                                            <input type="text" name="nama_kategori" id="nama_kategori" class="input w-full" wire:model='nama_kategori' required/>
                                        </div>
                                        @error('nama_kategori')
                                            {{ $message }}                                            
                                        @enderror
                                        <div class="flex flex-col w-full my-2 gap-1">
                                            <label class="label" for="tipe">Tipe</label>
                                            <select class="select w-full" name="tipe" id="tipe" wire:model='tipe' required>
                                                @foreach ($tipeList as $tipe)
                                                    <option value="{{ $tipe }}">{{ $tipe }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('tipe')
                                            {{ $message }}                                            
                                        @enderror
                                        <div class="w-full my-2">
                                            <button class="btn btn-sm btn-primary text-white w-full" wire:loading.attr="disabled">
                                                <span wire:loading class="loading loading-spinner loading-xs"></span>

                                                <span wire:loading.remove>
                                                    Edit Kategori
                                                </span>

                                                <span wire:loading>
                                                    Mengedit...
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Modal Delete --}}
                        <div class="modal" x-cloak x-show="modal === 'delete'" :class="{ 'modal-open': modal === 'delete' }" @click.self="modal = null">
                            <div class="modal-box">
                                <div>
                                    <span class="font-semibold">Hapus Data Kategori</span>
                                    <button type="button" @click="modal = null" wire:click='resetModel' class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">âœ•</button>
                                </div>
                                <form wire:submit='delete({{ $kategori_id }})' class="w-full my-3">
                                    <div class="flex flex-col mx-2">
                                        <div class="flex justify-center w-full">
                                           <span class="font-semibold text-center">Apakah Anda Yakin Untuk Menghapus Kategori Ini</span>
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
                                            </div>
                                            <div class="w-full my-2">
                                                <button class="btn btn-sm btn-error text-white w-full" wire:loading.attr="disabled">
                                                    <span wire:loading class="loading loading-spinner loading-xs"></span>

                                                    <span wire:loading.remove>
                                                        Hapus Kategori
                                                    </span>

                                                    <span wire:loading>
                                                        Menghapus...
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                {{ $kategoris->links() }}
            </div>
        </div>
    </div>
</div>
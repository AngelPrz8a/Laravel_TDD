<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Repositorio
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                
                <form action="{{ route("repositories.update",$repository) }}" method="post" class="max-w-md">
                    @csrf
                    @method("put")

                    <label for="" class="block fotn-medium text-sm text-gray-700">Url *</label>
                    <input type="text" class="form-input w-full rounded  shadow-sm" name="url" value="{{ $repository->url }}">

                    <label for="" class="block fotn-medium text-sm text-gray-700">Description *</label>
                    <textarea type="text" class="form-input w-full rounded  shadow-sm" name="description" >{{ $repository->description }}</textarea>
                
                    <hr class="my-4">

                    <input type="submit" value="Editar" class="bg-blue-500  text-white font-bold px-2 py-4 rounded-md">
                </form>

            </div>
        </div>
    </div>
</x-app-layout>

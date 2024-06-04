@extends('admin.layout.app')

@section('css')
    <style>
        /* Adiciona um estilo personalizado aqui se necessário */
    </style>
@endsection

@section('content')
    <!-- Page Heading -->
    <div class="page-header-content py-3">
        <ol class="breadcrumb mb-0 mt-4">
            <li class="breadcrumb-item"><a href="/">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Config</li>
        </ol>
    </div>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Configurações</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.config.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="motoboy_fone">Motoboy Telephone:</label>
                                <input type="text" id="motoboy_fone" name="motoboy_fone" value="{{ $config->motoboy_fone }}" class="form-control">
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" id="status" name="status" class="form-check-input" {{ $config->status ? 'checked' : '' }}>
                                    <label for="status" class="form-check-label">Enviar Para Motoquiro</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" id="chatbot" name="chatbot" class="form-check-input" {{ $config->chatbot ? 'checked' : '' }}>
                                    <label for="chatbot" class="form-check-label">Atendimento Automatico</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Adicione scripts aqui se necessário -->
@endsection

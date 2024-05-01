@push('styles')
    <style>
        .form-field {
            width: 400px;
            height: auto;
            min-height: 34px;
            border: 2px solid #dee2e6;
            padding: 8px;
            cursor: text;
            border-radius: 3px;
            box-shadow: 0 2px 6px rgba(25, 25, 25, 0.2);
        }

        .form-field .chips .chip {
            display: inline-block;
            width: auto;

            background-color: #0077b5;
            color: #fff;
            border-radius: 3px;
            margin: 2px;
            overflow: hidden;
        }

        .form-field .chips .chip {
            float: left;
        }

        .form-field .chips .chip .chip--button {
            padding: 8px;
            cursor: pointer;
            background-color: #004471;
            display: inline-block;
        }

        .form-field .chips .chip .chip--text {
            padding: 8px;
            cursor: no;
            display: inline-block;
            pointer-events: none
        }

        .form-field>input {
            padding: 15px;
            display: block;
            box-sizing: border-box;
            width: 100%;
            height: 34px;
            border: none;
            margin: 5px 0 0;
            display: inline-block;
            background-color: transparent;
        }
    </style>
@endpush
<div>
    <section class="card container-form-mails mx-auto px-3 pt-4 pb-2 mb-4">
        @if (session('error'))
            <p class="alert alert-danger text-center">{{ session('error') }}</p>
        @endif
        @if (session('success'))
            <p class="alert alert-success text-center">{{ session('success') }}</p>
        @endif
        <h2 class="text-center mb-4">Envio masivo de mails</h2>
        <form wire:submit="onSubmit">
            <div class="form-field mb-3 w-100">
                <div class="chips">
                    @foreach ($corporate_mails as $corporate_mail)
                        <div class="chip" @click="chipClickHandler(event)">
                            <span class="chip--text">{{ $corporate_mail }}</span>
                            <span class="chip--button">x</span>
                        </div>
                    @endforeach
                </div>
                <input placeholder="Ingrese los correos corporativos..." autofocus autocomplete="off"
                    class="chip-input" />
                @error('corporate_mails.*')
                    <span class="error text-danger">{{ $message }}</span>
                @enderror
                @error('corporate_mails')
                    <span class="error text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="subject" class="form-label">Asunto</label>
                <input id="subject" class="form-control" placeholder="Asunto..." type="text"
                    wire:model.blur="subject">
                @error('subject')
                    <span class="error text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="cvs" class="form-label">Cargar CVs</label>
                <input id="cvs" class="form-control" type="file" id="formFileMultiple" 
                    wire:model.live="files"
                    multiple accept=".pdf"
                >


                @error('files')
                    <span class="error text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <div class="d-grid gap-2">
                    <button class="btn btn-success fw-bold" type="submit">Enviar</button>
                    @if (!empty($files))
                        <ul style="list-style: none;" class="p-0">
                            @foreach ($files as $file)
                                <li style="border: 1px solid silver; background:rgba(50, 50, 50, 0.1)"  class="p-0 px-3 py-2">{{ $file->getClientOriginalName() }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p>No hay archivos seleccionados</p>
                    @endif
                    {{-- <button class="btn btn-info fw-bold" type="submit">Importar CSV</button> --}}
                </div>
            </div>
        </form>
    </section>
</div>

@script
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Runs immediately after Livewire has finished initializing
            // on the page...

            var input = document.querySelector(".chip-input");
            var chips = document.querySelector(".chips");

            // document.querySelector(".form-field")
            //     .addEventListener('click', () => {
            //         input.style.display = 'block';
            //         input.focus();
            //     });

            // input.addEventListener('blur', () => {
            //     input.style.display = 'none';
            // });

            input.addEventListener('keypress', function(event) {
                if (event.which === 13) {

                    event.preventDefault();
                    $wire.corporate_mails.push(input.value);
                    console.log($wire.corporate_mails);

                    $wire.$refresh();

                    // chips.appendChild(function() {
                    //     var _chip = document.createElement('div');


                    //     _chip.addEventListener('click', chipClickHandler);


                    //     _chip.append(
                    //         (function() {
                    //             var _chip_text = document.createElement('span');
                    //             _chip_text.classList.add('chip--text');
                    //             _chip_text.innerHTML = input.value;

                    //             return _chip_text;
                    //         })(),
                    //         (function() {
                    //             var _chip_button = document.createElement('span');
                    //             _chip_button.classList.add('chip--button');
                    //             _chip_button.innerHTML = 'x';

                    //             return _chip_button;
                    //         })()
                    //     );

                    //     return _chip;
                    // }());


                    input.value = '';
                }
            });


            // Livewire.on('chipClickHandler', ($el) => {

            //     let index = Array.from(event.currentTarget.parentNode.children).indexOf(event
            //         .currentTarget);

            //     $wire.corporate_mails.pop(index);
            //     $wire.$refresh();
            //     // chips.removeChild(event.currentTarget);
            // })
            // function chipClickHandler(event) {
            //     let index = Array.from(event.currentTarget.parentNode.children).indexOf(event.currentTarget);

            //     $wire.corporate_mails.pop(index);
            //     $wire.$refresh();
            //     // chips.removeChild(event.currentTarget);
            // }
            window.chipClickHandler = function(event) {
                let index = Array.from(event.currentTarget.parentNode.children).indexOf(event.currentTarget);

                $wire.corporate_mails.pop(index);
                $wire.$refresh();
                // chips.removeChild(event.currentTarget);
            };
        })
    </script>
@endscript

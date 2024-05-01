<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Storage;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

#[Title('Envio de emails masivamente')]
#[Layout('components.layouts.app')]
class CreateEmail extends Component
{

    use WithFileUploads;

    #[Validate]
    public $corporate_mails = [];
    public $subject = '';
    public $files = [];
    public $files_aux = [];


    public function rules()
    {
        return [
            'subject' => 'required',
            'files' => 'required',
            'corporate_mails' => 'required',
            'corporate_mails.*' => 'email'
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Este campo es obligatorio',
            'corporate_mails.*.email' => 'Todos los elementos deben ser en formato email'
        ];
    }

    public function updatedFiles($newFiles, $oldFiles)
    {
        $this->files_aux = array_merge($this->files_aux, $newFiles);
        $this->files = $this->files_aux;
    }

    public function onSubmit()
    {
        $validated = $this->validate();
        Storage::deleteDirectory('public/tmp_pdfs/');
        foreach ($this->files as $pdf) {
            $pdf->store('public/tmp_pdfs');
        }
        $pdfs_fullpath = Storage::allFiles('public/tmp_pdfs');
        $basename_pdfs = [];
        foreach ($pdfs_fullpath as $pdf_fullpath) {
            $basename_pdf = pathinfo($pdf_fullpath, PATHINFO_BASENAME);
            array_push($basename_pdfs, $basename_pdf);
        }

        $mail = new PHPMailer(true);
        /* Email SMTP Settings */
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = env('MAIL_HOST');
        $mail->SMTPAuth = true;
        $mail->Username = env('MAIL_USERNAME');
        $mail->Password = env('MAIL_PASSWORD');
        $mail->SMTPSecure = env('MAIL_ENCRYPTION');
        $mail->Port = env('MAIL_PORT');

        foreach ($this->corporate_mails as $corporate_mail) {
            foreach ($basename_pdfs as $basename_pdf) {
                try {
                    $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                    $mail->addAddress($corporate_mail);
                    $mail->AddAttachment(public_path('storage/tmp_pdfs/' . $basename_pdf), 'CV');

                    $mail->isHTML(true);

                    $mail->Subject = $this->subject;
                    $mail->Body    = ' ';
                    $mail->send();
                    // Limpiar la instancia actual para el próximo correo electrónico
                    $mail->clearAddresses();
                    $mail->clearAttachments();
                    $mail->clearAllRecipients();
                    $mail->clearCCs();
                    $mail->clearBCCs();
                    $mail->clearReplyTos();
                    $mail->clearCustomHeaders();
                    $mail->clearAttachments();
                } catch (Exception $e) {
                    return back()->with('error', 'Message could not be sent./' . $e->getMessage() . '/' . $e->getLine());
                }
            }
        }

        return back()->with('success', 'Todos los emails fueron enviados correctamente');
    }
    public function render()
    {
        return view('livewire.create-email');
    }
}

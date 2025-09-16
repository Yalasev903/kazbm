<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationCallRequest;
use App\Http\Requests\ApplicationConsultationRequest;
use App\Models\Applications\ApplicationCall;
use App\Models\Applications\ApplicationConsultation;

class FeedbackController extends Controller
{

    # Формы обратной связи
    public function consultation(ApplicationConsultationRequest $request, ApplicationConsultation $application)
    {

        $application->setDataAttributes($request->only(['email', 'name', 'message']));
        if ($application->save()) {
//            $subject = 'kaz-remont.kz: Заявка на консультацию' . ' №' . $application->id;
//            (new MailService)->send($subject, $application);
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error'], 500);
    }

    public function call(ApplicationCallRequest $request, ApplicationCall $application)
    {

        $application->setDataAttributes($request->only(['phone', 'name', 'message']));
        if ($application->save()) {
//            $subject = 'kazbm.kz: Заявка на звонок' . ' №' . $application->id;
//            (new MailService)->send($subject, $application);
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error'], 500);
    }
}

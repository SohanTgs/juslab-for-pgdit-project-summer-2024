<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\CurlRequest;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserLogin;
use App\Models\Withdrawal;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    private $datasetPath = '/home/sohan/chatbot/bot/datasets';

    public function dashboard()
    {
        return to_route('admin.knowledge.base.all');

        $pageTitle = 'Dashboard';

        // User Info
        $widget['total_users']             = User::count();
        $widget['verified_users']          = User::active()->count();
        $widget['email_unverified_users']  = User::emailUnverified()->count();
        $widget['mobile_unverified_users'] = User::mobileUnverified()->count();


        // user Browsing, Country, Operating Log
        $userLoginData = UserLogin::where('created_at', '>=', Carbon::now()->subDays(30))->get(['browser', 'os', 'country']);

        $chart['user_browser_counter'] = $userLoginData->groupBy('browser')->map(function ($item, $key) {
            return collect($item)->count();
        });
        $chart['user_os_counter'] = $userLoginData->groupBy('os')->map(function ($item, $key) {
            return collect($item)->count();
        });
        $chart['user_country_counter'] = $userLoginData->groupBy('country')->map(function ($item, $key) {
            return collect($item)->count();
        })->sort()->reverse()->take(5);


        $deposit['total_deposit_amount']        = Deposit::successful()->sum('amount');
        $deposit['total_deposit_pending']       = Deposit::pending()->count();
        $deposit['total_deposit_rejected']      = Deposit::rejected()->count();
        $deposit['total_deposit_charge']        = Deposit::successful()->sum('charge');

        $withdrawals['total_withdraw_amount']   = Withdrawal::approved()->sum('amount');
        $withdrawals['total_withdraw_pending']  = Withdrawal::pending()->count();
        $withdrawals['total_withdraw_rejected'] = Withdrawal::rejected()->count();
        $withdrawals['total_withdraw_charge']   = Withdrawal::approved()->sum('charge');

        return view('admin.dashboard', compact('pageTitle', 'widget', 'chart','deposit','withdrawals'));
    }




    public function depositAndWithdrawReport(Request $request) {

        $diffInDays = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date));

        $groupBy = $diffInDays > 30 ? 'months' : 'days';
        $format = $diffInDays > 30 ? '%M-%Y'  : '%d-%M-%Y';

        if ($groupBy == 'days') {
            $dates = $this->getAllDates($request->start_date, $request->end_date);
        } else {
            $dates = $this->getAllMonths($request->start_date, $request->end_date);
        }
        $deposits = Deposit::successful()
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->latest()
            ->groupBy('created_on')
            ->get();


        $withdrawals = Withdrawal::approved()
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->latest()
            ->groupBy('created_on')
            ->get();

        $data = [];

        foreach ($dates as $date) {
            $data[] = [
                'created_on' => $date,
                'deposits' => getAmount($deposits->where('created_on', $date)->first()?->amount ?? 0),
                'withdrawals' => getAmount($withdrawals->where('created_on', $date)->first()?->amount ?? 0)
            ];
        }

        $data = collect($data);

        // Monthly Deposit & Withdraw Report Graph
        $report['created_on']   = $data->pluck('created_on');
        $report['data']     = [
            [
                'name' => 'Deposited',
                'data' => $data->pluck('deposits')
            ],
            [
                'name' => 'Withdrawn',
                'data' => $data->pluck('withdrawals')
            ]
        ];

        return response()->json($report);
    }

    public function transactionReport(Request $request) {

        $diffInDays = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date));

        $groupBy = $diffInDays > 30 ? 'months' : 'days';
        $format = $diffInDays > 30 ? '%M-%Y'  : '%d-%M-%Y';

        if ($groupBy == 'days') {
            $dates = $this->getAllDates($request->start_date, $request->end_date);
        } else {
            $dates = $this->getAllMonths($request->start_date, $request->end_date);
        }

        $plusTransactions   = Transaction::where('trx_type','+')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->latest()
            ->groupBy('created_on')
            ->get();

        $minusTransactions  = Transaction::where('trx_type','-')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->latest()
            ->groupBy('created_on')
            ->get();


        $data = [];

        foreach ($dates as $date) {
            $data[] = [
                'created_on' => $date,
                'credits' => getAmount($plusTransactions->where('created_on', $date)->first()?->amount ?? 0),
                'debits' => getAmount($minusTransactions->where('created_on', $date)->first()?->amount ?? 0)
            ];
        }

        $data = collect($data);

        // Monthly Deposit & Withdraw Report Graph
        $report['created_on']   = $data->pluck('created_on');
        $report['data']     = [
            [
                'name' => 'Plus Transactions',
                'data' => $data->pluck('credits')
            ],
            [
                'name' => 'Minus Transactions',
                'data' => $data->pluck('debits')
            ]
        ];

        return response()->json($report);
    }


    private function getAllDates($startDate, $endDate) {
        $dates = [];
        $currentDate = new \DateTime($startDate);
        $endDate = new \DateTime($endDate);

        while ($currentDate <= $endDate) {
            $dates[] = $currentDate->format('d-F-Y');
            $currentDate->modify('+1 day');
        }

        return $dates;
    }

    private function  getAllMonths($startDate, $endDate) {
        if ($endDate > now()) {
            $endDate = now()->format('Y-m-d');
        }

        $startDate = new \DateTime($startDate);
        $endDate = new \DateTime($endDate);

        $months = [];

        while ($startDate <= $endDate) {
            $months[] = $startDate->format('F-Y');
            $startDate->modify('+1 month');
        }

        return $months;
    }


    public function profile()
    {
        $pageTitle = 'Profile';
        $admin = auth('admin')->user();
        return view('admin.profile', compact('pageTitle', 'admin'));
    }

    public function profileUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'image' => ['nullable','image',new FileTypeValidate(['jpg','jpeg','png'])]
        ]);
        $user = auth('admin')->user();

        if ($request->hasFile('image')) {
            try {
                $old = $user->image;
                $user->image = fileUploader($request->image, getFilePath('adminProfile'), getFileSize('adminProfile'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        $notify[] = ['success', 'Profile updated successfully'];
        return to_route('admin.profile')->withNotify($notify);
    }

    public function password()
    {
        $pageTitle = 'Password Setting';
        $admin = auth('admin')->user();
        return view('admin.password', compact('pageTitle', 'admin'));
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:5|confirmed',
        ]);

        $user = auth('admin')->user();
        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', 'Password doesn\'t match!!'];
            return back()->withNotify($notify);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        $notify[] = ['success', 'Password changed successfully.'];
        return to_route('admin.password')->withNotify($notify);
    }

    public function notifications(){
        $notifications = AdminNotification::orderBy('id','desc')->with('user')->paginate(getPaginate());
        $hasUnread = AdminNotification::where('is_read',Status::NO)->exists();
        $hasNotification = AdminNotification::exists();
        $pageTitle = 'Notifications';
        return view('admin.notifications',compact('pageTitle','notifications','hasUnread','hasNotification'));
    }


    public function notificationRead($id){
        $notification = AdminNotification::findOrFail($id);
        $notification->is_read = Status::YES;
        $notification->save();
        $url = $notification->click_url;
        if ($url == '#') {
            $url = url()->previous();
        }
        return redirect($url);
    }

    public function requestReport()
    {
        $pageTitle = 'Your Listed Report & Request';
        $arr['app_name'] = systemDetails()['name'];
        $arr['app_url'] = env('APP_URL');
        $arr['purchase_code'] = env('PURCHASECODE');
        $url = "https://license.viserlab.com/issue/get?".http_build_query($arr);
        $response = CurlRequest::curlContent($url);
        $response = json_decode($response);
        if (!$response || !isset($response->status) || !isset($response->message)) {
            return to_route('admin.dashboard')->withErrors('Something went wrong');
        }
        if ($response->status == 'error') {
            return to_route('admin.dashboard')->withErrors($response->message);
        }
        $reports = $response->message[0];
        return view('admin.reports',compact('reports','pageTitle'));
    }

    public function reportSubmit(Request $request)
    {
        $request->validate([
            'type'=>'required|in:bug,feature',
            'message'=>'required',
        ]);
        $url = 'https://license.viserlab.com/issue/add';

        $arr['app_name'] = systemDetails()['name'];
        $arr['app_url'] = env('APP_URL');
        $arr['purchase_code'] = env('PURCHASECODE');
        $arr['req_type'] = $request->type;
        $arr['message'] = $request->message;
        $response = CurlRequest::curlPostContent($url,$arr);
        $response = json_decode($response);
        if (!$response || !isset($response->status) || !isset($response->message)) {
            return to_route('admin.dashboard')->withErrors('Something went wrong');
        }
        if ($response->status == 'error') {
            return back()->withErrors($response->message);
        }
        $notify[] = ['success',$response->message];
        return back()->withNotify($notify);
    }

    public function readAllNotification(){
        AdminNotification::where('is_read',Status::NO)->update([
            'is_read'=>Status::YES
        ]);
        $notify[] = ['success','Notifications read successfully'];
        return back()->withNotify($notify);
    }

    public function deleteAllNotification(){
        AdminNotification::truncate();
        $notify[] = ['success','Notifications deleted successfully'];
        return back()->withNotify($notify);
    }

    public function deleteSingleNotification($id){
        AdminNotification::where('id',$id)->delete();
        $notify[] = ['success','Notification deleted successfully'];
        return back()->withNotify($notify);
    }

    public function downloadAttachment($fileHash)
    {
        $filePath = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $title = slug(gs('site_name')).'- attachments.'.$extension;
        try {
            $mimetype = mime_content_type($filePath);
        } catch (\Exception $e) {
            $notify[] = ['error','File does not exists'];
            return back()->withNotify($notify);
        }
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function allKnowledgeBase()
    {
        $pageTitle = 'All Knowledge Base';
        $admin = auth('admin')->user();

        // Get all .json files from the absolute path
        $jsonFiles = glob($this->datasetPath . '/*.json');

        return view('admin.knowledge', compact('pageTitle', 'admin', 'jsonFiles'))
            ->with('datasetPath', $this->datasetPath);
    }

    public function addKnowledgeBase(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z\s]+$/',
                function ($attribute, $value, $fail) {
                    if (preg_match('/\d/', $value)) {
                        $fail('The '.$attribute.' must not contain numbers.');
                    }
                    if (preg_match('/[^a-zA-Z\s]/', $value)) {
                        $fail('The '.$attribute.' must only contain letters and spaces.');
                    }
                }
            ],
        ], [
            'name.regex' => 'Only letters (a-z, A-Z) and spaces are allowed',
            'name.max' => 'Maximum 50 characters allowed'
        ]);

        // Ensure directory exists
        if (!file_exists($this->datasetPath)) {
            mkdir($this->datasetPath, 0755, true);
        }

        // Convert name to filename format (replace spaces with underscores and lowercase)
        $filename = slug($request->name, '_') . '.json';
        $filePath = $this->datasetPath . '/' . $filename;

        // Check if file already exists
        if (file_exists($filePath)) {
            $notify[] = ['error', 'A knowledge base with this name already exists'];
            return back()->withNotify($notify);
        }

        // Create empty JSON structure
        $jsonContent = [];

        // Save the file
        if (file_put_contents($filePath, json_encode($jsonContent, JSON_PRETTY_PRINT))) {
            $notify[] = ['success', 'Knowledge base "'.$request->name.'" created successfully'];
            return back()->withNotify($notify);
        }

        $notify[] = ['error', 'Failed to create knowledge base'];
        return back()->withNotify($notify);
    }

    public function deleteKnowledgeBase($fileName)
    {
        // Validate the filename
        if (!preg_match('/^[a-zA-Z0-9_\-]+\.json$/', $fileName)) {
            $notify[] = ['error', 'Invalid filename format'];
            return back()->withNotify($notify);
        }

        $filePath = $this->datasetPath . '/' . $fileName;

        // Check if file exists
        if (!file_exists($filePath)) {
            $notify[] = ['error', 'The requested file does not exist'];
            return back()->withNotify($notify);
        }

        // Prevent deletion of default.json
        if ($fileName == 'default.json') {
            $notify[] = ['error', 'Cannot delete the default knowledge base'];
            return back()->withNotify($notify);
        }

        // Attempt to delete the file
        if (unlink($filePath)) {
            $notify[] = ['success', 'Knowledge base deleted successfully'];
            return back()->withNotify($notify);
        }

        $notify[] = ['error', 'Failed to delete knowledge base'];
        return back()->withNotify($notify);
    }

    public function allDataset()
    {
        $pageTitle = 'All Dataset';
        $admin = auth('admin')->user();

        $jsonFiles = glob($this->datasetPath . '/*.json');

        return view('admin.dataset', compact('pageTitle', 'admin', 'jsonFiles'))
            ->with('datasetPath', $this->datasetPath);
    }

    public function editDataset($fileName)
    {
        // Validate filename
        if (!preg_match('/^[a-zA-Z0-9_\-]+\.json$/', $fileName)) {
            $notify[] = ['error', 'Invalid filename'];
            return back()->withNotify($notify);
        }

        $filePath = $this->datasetPath . '/' . $fileName;

        // Check if file exists
        if (!file_exists($filePath)) {
            $notify[] = ['error', 'Dataset file not found'];
            return back()->withNotify($notify);
        }

        // Read and decode JSON content
        $content = json_decode(file_get_contents($filePath), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $notify[] = ['error', 'Invalid JSON format in file'];
            return back()->withNotify($notify);
        }

        $pageTitle = 'Edit Dataset: ' . str_replace('.json', '', $fileName);
        $admin = auth('admin')->user();

        return view('admin.edit_dataset', compact('pageTitle', 'admin', 'content', 'fileName'));
    }

    public function updateDataset(Request $request, $fileName)
    {
        // Validate filename
        if (!preg_match('/^[a-zA-Z0-9_\-]+\.json$/', $fileName)) {
            $notify[] = ['error', 'Invalid filename'];
            return back()->withNotify($notify);
        }

        $filePath = $this->datasetPath . '/' . $fileName;

        // Check if file exists
        if (!file_exists($filePath)) {
            $notify[] = ['error', 'Dataset file not found'];
            return back()->withNotify($notify);
        }

        // Validate request data
        $request->validate([
            'content' => 'required|array',
            'content.*.question' => 'required|string|max:500',
            'content.*.answer' => 'required|array|min:1',
            'content.*.answer.*' => 'required|string|max:1000'
        ], [
            'content.*.question.required' => 'Each question field is required',
            'content.*.answer.required' => 'Each question must have at least one answer',
            'content.*.answer.*.required' => 'Each answer field is required'
        ]);

        // Prepare data for JSON
        $content = [];
        foreach ($request->content as $item) {
            $content[] = [
                'question' => $item['question'],
                'answer' => array_values($item['answer']) // Reindex array
            ];
        }

        // Save to file
        try {
            // Save dataset changes
            file_put_contents($filePath, json_encode($content, JSON_PRETTY_PRINT));
        
            // Trigger Django restart via Passenger
            $restartFile = '/home/sohan/chatbot/tmp/restart.txt';
            if (!touch($restartFile)) {
                throw new \Exception('Failed to trigger Django restart');
            }
        
            $notify[] = ['success', 'Dataset updated successfully'];
            return back()->withNotify($notify);
        
        } catch (\Exception $e) {
            $notify[] = ['error', 'Failed to update dataset: ' . $e->getMessage()];
            return back()->withNotify($notify);
        }
    }
}

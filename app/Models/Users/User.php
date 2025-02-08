<?php

namespace App\Models\Users;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Extenders\Models\BaseUser as Authenticatable;
use App\Models\AdminSetups\Client;
use App\Models\AdminSetups\Company;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use App\Notifications\Web\Auth\VerifyEmail;
use App\Notifications\Web\Auth\ResetPassword;
use Password;

use App\Models\PurchaseOrders\PurchaseOrder;
use App\Models\PurchaseOrders\PurchaseOrderLine;
use App\Models\Invoices\VendorInvoice;
use App\Models\Invoices\VendorInvoiceLine;

use App\Models\SalesOrders\SalesOrder;
use App\Models\SalesOrders\SalesOrderLine;
use App\Models\Invoices\CustomerInvoice;
use App\Models\Invoices\CustomerInvoiceLine;

use App\Models\AdminSetups\Department;
use App\Models\AdminSetups\Position;

use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use  App\Helpers\ArrayHelpers;
use App\Models\LedgerSetups\DocumentCodeControls\DocumentCodeControl;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use SoftDeletes;
    use HasRoles;


    /**
     * Overrides default reset password notification
     */
    public function sendPasswordResetNotification($token) {
        $this->notify(new ResetPassword($token));
    }

    public function sendEmailVerificationNotification() {
        $this->notify(new VerifyEmail);
    }

    public function broker() {
        return Password::broker('users');
    }
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'middle_name', 'email', 'status', 'company_id', 'department_id', 'position_id', 'active_from', 'active_to', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [ 'fullname' ];

    /**
     * Relationships
     */
    
    public function created_purchase_orders() {
        return $this->hasMany(PurchaseOrder::class, 'created_by', 'id');
    }

    public function updated_purchase_orders() {
        return $this->hasMany(PurchaseOrder::class, 'updated_by', 'id');
    }

    public function confirmed_purchase_orders() {
        return $this->hasMany(PurchaseOrder::class, 'confirmed_by', 'id');
    }

    public function approved_purchase_orders() {
        return $this->hasMany(PurchaseOrder::class, 'approver', 'id');
    }

    public function created_purchase_order_lines() {
        return $this->hasMany(PurchaseOrderLine::class, 'created_by', 'id');
    }

    public function updated_purchase_order_lines() {
        return $this->hasMany(PurchaseOrderLine::class, 'updated_by', 'id');
    }

    public function created_vendor_invoices() {
        return $this->hasMany(VendorInvoice::class, 'created_by', 'id');
    }

    public function updated_vendor_invoices() {
        return $this->hasMany(VendorInvoice::class, 'updated_by', 'id');
    }

    public function posted_vendor_invoices() {
        return $this->hasMany(VendorInvoice::class, 'posted_by', 'id');
    }

    public function approved_vendor_invoices() {
        return $this->hasMany(VendorInvoice::class, 'approved_by', 'id');
    }

    public function created_vendor_invoice_lines() {
        return $this->hasMany(VendorInvoiceLine::class, 'created_by', 'id');
    }

    public function updated_vendor_invoice_lines() {
        return $this->hasMany(VendorInvoiceLine::class, 'updated_by', 'id');
    }

    public function confirmed_sales_orders() {
        return $this->hasMany(SalesOrder::class, 'confirmed_by', 'id');
    }

    public function created_sales_order_lines() {
        return $this->hasMany(SalesOrderLine::class, 'created_by', 'id');
    }

    public function updated_sales_order_lines() {
        return $this->hasMany(SalesOrderLine::class, 'updated_by', 'id');
    }

    public function created_customer_invoices() {
        return $this->hasMany(CustomerInvoice::class, 'created_by', 'id');
    }

    public function updated_customer_invoices() {
        return $this->hasMany(CustomerInvoice::class, 'updated_by', 'id');
    }

    public function posted_customer_invoices() {
        return $this->hasMany(CustomerInvoice::class, 'posted_by', 'id');
    }

    public function approved_customer_invoices() {
        return $this->hasMany(CustomerInvoice::class, 'approved_by', 'id');
    }

    public function created_customer_invoice_lines() {
        return $this->hasMany(CustomerInvoiceLine::class, 'created_by', 'id');
    }

    public function updated_customer_invoice_lines() {
        return $this->hasMany(CustomerInvoiceLine::class, 'updated_by', 'id');
    }

    public function company() {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function department() {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function position() {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function clients() {
        return $this->belongsToMany(Client::class, 'users_clients', 'user_id', 'client_id');
    }

    /**
     * Setters
     */

    public static function store($request, $item = null, $columns = ['first_name', 'last_name', 'middle_name', 'email', 'status', 'company_id','department_id', 'position_id', 'active_from', 'active_to'])
	{   
        if($request['type']) {
           if($request['type'] == 'system-admin') {
                $request['company_id'] = 0;
            }
            $request['department_id'] = 0;
            $request['position_id'] = 0;
        }

	    $vars = $request->only($columns);
                
	    if (!$item) {
            $vars['created_at'] = Carbon::now();
            $vars['updated_at'] = Carbon::now();
            $vars['password'] = Hash::make(str_random(32));
	        $item = static::create($vars);
            $token = Password::getRepository()->create($item);
            $item->sendPasswordResetNotification($token);
	    } else {
            $vars['updated_at'] = Carbon::now();
	        $item->update($vars);
	    }

        // assign role & permission
        if($request['type']) {
            if($request['type'] == 'company-admin') {
                $item->assignRole('Company Admin');
            }
            if($request['type'] == 'system-admin') {
                $item->assignRole('Admin');
            }
         }else {
            $item->syncRoles([]);
         }

        return $item;
	}

    public function updatePermissions($request) {
        if (!$this->isPermissionEditable()) {
            throw ValidationException::withMessages([
                'permissions' => 'Permissions cannot be updated.',
            ]);
        }

        if (!$request->input('permissions')) {
            throw ValidationException::withMessages([
                'permissions' => 'User must have atleast 1 permission',
            ]);
        }

        $newIds = $request->input('permissions');
        $result = ArrayHelpers::diff($newIds, $this->permissions()->pluck('id')->toArray());

        $this->syncPermissions($newIds);

        if ($result['action']) {
            unset($result['action']);
            activity()
                ->performedOn($this)
                ->causedBy($request->user())
                ->withProperties($result)
                ->log($this->renderLogName() . " permissions has been updated");
        }

        return $this;
    }

    /**
	 * Checkers
	 */
    public function isPermissionEditable() {
        return $this->id !== 1;
    }

    public function isArchiveable(): bool {
        return $this->id !== 1;
    }

    public function isRestorable(): bool {
        return $this->id !== 1;
    }

	/**
	 * Renderers
	 */
	
	public function renderShowUrl() {
        return route('users.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('users.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('users.restore', $this->id);
    }

    public function withCompanyRenderShowUrl() {
        return route('users.show', [$this->id, $this->company_id]);
    }

    public function adminRenderShowUrl() {
        $type = $this->getRoleNames()->first() == 'Admin' ? 'system-admin' : 'company-admin';
        return route('admin-users.show', [$this->id, $type]);
    }

    public function renderName($formats = 'f l') {
        $result = '';
        $count = 0;
        $formats = explode(' ', strtolower($formats));

        foreach ($formats as $format) {
            if ($count > 0) {
                $result .= ' ';
            }

            switch ($format) {
                case 'f':
                    $result .= $this->first_name;
                    break;
                
                case 'l':
                    $result .= $this->last_name;
                    break;
            }

            $count++;
        }

        return $result;
    }


    /**
     * Appends
     */
    
    public function getFullnameAttribute() {
        return $this->first_name. ' '. $this->last_name;
    }

    /**
     * Getters
     */

     // return clients
     public static function getClients($module = null, $model = null) {
        if(auth()->check()) {
            $user = auth()->user();
            $clients = $user->clients;

            if($user->hasRole('Company Admin')) {
                $clients = Client::where('company_id',$user->company_id)->get();
            }

            if($module && $model) {
                $clients = collect($clients)->map(function ($client) use($module, $model) {
                    $client->code = DocumentCodeControl::generateCode($client->id, $module, $model);
                    return $client;
                });
            }


            return $clients;
        }
     }

}

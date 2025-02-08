<li class="nav-item has-treeview {{ $checker->route->areOnRoutes([
		'fiscal-calendars.index' , 'fiscal-calendars.create', 'fiscal-calendars.show',
		'ledger-calendars.index',
		'fiscals.index' , 'fiscals.create', 'fiscals.show',
		'date-intervals.index' , 'date-intervals.create', 'date-intervals.show',
	]) }}">
	<a href="javascript:void(0)" class="nav-link">
	    <i class="nav-icon fas fa-calendar-alt"></i>
	    <p>
	        Calendar And Periods
	        <i class="right fa fa-angle-left"></i>
	    </p>
	</a>

	<ul class="nav nav-treeview">

		<li class="nav-item">
		    <a href="{{ route('fiscal-calendars.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		        'fiscal-calendars.index' , 'fiscal-calendars.create', 'fiscal-calendars.show',
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Fiscal Calendar
		        </p>
		    </a>
		</li>

		<li class="nav-item">
		    <a href="{{ route('ledger-calendars.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		        'ledger-calendars.index' 
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Ledger Calendar
		        </p>
		    </a>
		</li>

		<li class="nav-item">
		    <a href="{{ route('fiscals.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		        'fiscals.index' , 'fiscals.create', 'fiscals.show',
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Fiscal Year
		        </p>
		    </a>
		</li>

		<li class="nav-item">
		    <a href="{{ route('date-intervals.index') }}" class="nav-link {{ $checker->route->areOnRoutes([
		        'date-intervals.index' , 'date-intervals.create', 'date-intervals.show',
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Date Intervals
		        </p>
		    </a>
		</li>

		<li class="nav-item">
		    <a href="#" class="nav-link {{ $checker->route->areOnRoutes([
		        
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Period
		        </p>
		    </a>
		</li>

		<li class="nav-item">
		    <a href="#" class="nav-link {{ $checker->route->areOnRoutes([
		        
		    ]) }}">
		        <i class="nav-icon far fa-circle"></i>
		        <p>
		            Closing Periods
		        </p>
		    </a>
		</li>
	</ul>
</li>
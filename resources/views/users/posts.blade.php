<x-waterhole::user-profile :user="$user" :title="$user->name.'\'s '.$posts->currentSort()->name().' Posts'">
    <x-waterhole::feed :feed="$posts"/>
</x-waterhole::user-profile>

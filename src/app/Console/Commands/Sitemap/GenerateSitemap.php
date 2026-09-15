<?php


namespace App\Console\Commands\Sitemap;
use App\Models\App\JobPost\JobPost;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';
    private array $jobPostLinks = [];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->prepareJobPostLinks();

        $siteMap = Sitemap::create();

        foreach ($this->jobPostLinks as $link){
            $siteMap->add(Url::create($link)
                ->setLastModificationDate(Carbon::yesterday())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
                ->setPriority(0.1));
        }
        if(env('APP_ENV') === 'production'){
            $siteMap->writeToFile(str_replace('src','',base_path()).'sitemap.xml');
            $this->pingToUpdate();
        }else{
            $siteMap->writeToFile(public_path('sitemap.xml'));
        }
        return  true;
    }

    public function pingToUpdate()
    {
        /*//Sitemap for GOOGLE using HTTP Client
        $url = "https://www.google.com/ping?sitemap=".env('APP_URL').'/sitemap.xml';
        $response = Http::get($url);
        return $response->status();*/

        //Sitemap for GOOGLE using curl
        $url = "https://www.google.com/ping?sitemap=".env('APP_URL').'/sitemap.xml';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        return curl_getinfo($ch, CURLINFO_HTTP_CODE);
    }

    public function prepareJobPostLinks()
    {
        $jobPosts = JobPost::query()->with('status')
            ->whereHas('status', function ($query)  {
                $query->where('name', 'status_open');
            })
            ->get();

        foreach ($jobPosts as $post){
            $link = $post->slug;
            $this->jobPostLinks[] = $link;
        }
    }
}
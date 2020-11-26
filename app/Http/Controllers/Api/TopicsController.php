<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\TopicRequest;
use App\Http\Resources\TopicResource;
use App\Models\Topic;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Models\User;
use App\Http\Queries\TopicQuery;

class TopicsController extends Controller
{
    // 新增话题
    public function store(TopicRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = $request->user()->id;
        $topic->save();

        return new TopicResource($topic);
    }

    //修改话题
    public function update(TopicRequest $request,Topic $topic)
    {
        //先确认话题的发布者 ID 为你当前 token 所属用户的 ID。
        $this->authorize('update', $topic);

        $topic->update($request->all());
        return new TopicResource($topic);
    }

    //删除话题
    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);
        $topic->delete();
        return response(null, 204);
    }


    // 话题列表
    public function index(Request $request, TopicQuery $query)
    {

        $topics = $query->paginate();
//dd($topics);
foreach ($topics as $k=>$v){
    dd($v->user());
    dd($v->user()->avatar);
}
        return TopicResource::collection($topics);
    }
    // 某个用户发布的话题
    public function userIndex(Request $request, User $user, TopicQuery $query)
    {
        $topics = $query->where('user_id', $user->id)->paginate();

        return TopicResource::collection($topics);
    }
    // 单个话题详情
    public function show($topicId, TopicQuery $query)
    {
        $topic = $query->findOrFail($topicId);
        return new TopicResource($topic);
    }

    // 话题列表
    /*
    public function index(Request $request, Topic $topic)
    {
//        $query = $topic->query();
//        if ($categoryId = $request->category_id) {
//            $query->where('category_id', $categoryId);
//        }
//        $topics = $query->with('user', 'category')
//            ->withOrder($request->order)->paginate();

        $topics = QueryBuilder::for(Topic::class)
            ->allowedIncludes('user', 'category')
            ->allowedFilters([
                'title',
                AllowedFilter::exact('category_id'),
                AllowedFilter::scope('withOrder')->default('recentReplied'),
            ])
            ->paginate();
        return TopicResource::collection($topics);
    }


    // 某个用户发布的话题
    public function userIndex(Request $request, User $user)
    {
        $query = $user->topics()->getQuery();

        $topics = QueryBuilder::for($query)
            ->allowedIncludes('user', 'category')
            ->allowedFilters([
                'title',
                AllowedFilter::exact('category_id'),
                AllowedFilter::scope('withOrder')->default('recentReplied'),
            ])
            ->paginate();

        return TopicResource::collection($topics);
    }

    // 单个话题详情
    public function show($topicId)
    {
        $topic = QueryBuilder::for(Topic::class)
            ->allowedIncludes('user', 'category')
            ->findOrFail($topicId);

        return new TopicResource($topic);
    }
    */
}

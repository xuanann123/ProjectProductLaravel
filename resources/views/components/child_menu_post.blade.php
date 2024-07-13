 @if (count( $categoryParent->categoryChild) > 0 )
                                 <ul class="sub-menu">
                                    @foreach ($categoryParent->categoryChild as $categoryChildent) 
                                <li>
                                    <a href="{{ route("detail.catPost", $categoryChildent->id) }}" title="">{{ $categoryChildent->post_cat_name }}</a>
                                    @if ($categoryParent->categoryChild->count())
                                        @include('components.child_menu', ["categoryParent" => $categoryChildent])
                                    @endif
                                </li>
                                 @endforeach
                            </ul>
@endif
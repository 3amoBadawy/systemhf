<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * عرض قائمة الفئات
     */
    public function index(): View
    {
        $categories = Category::orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('categories.index', compact('categories'));
    }

    /**
     * عرض نموذج إنشاء فئة جديدة
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * حفظ فئة جديدة
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            $categoryData = [
                'name' => $request->input('name'),
                'name_ar' => $request->input('name_ar'),
                'description' => $request->input('description'),
                'sort_order' => $request->input('sort_order') ?? 0,
                'status' => 'active',
            ];

            $category = Category::create($categoryData);

            // رفع صورة الفئة
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('categories', 'public');
                $category->update(['image' => $imagePath]);
            }

            return redirect()->route('categories.index')
                ->with('success', 'تم إنشاء الفئة بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'حدث خطأ أثناء إنشاء الفئة: '.$e->getMessage()]);
        }
    }

    /**
     * عرض تفاصيل الفئة
     */
    public function show(Category $category): View
    {
        $category->load(['products']);

        return view('categories.show', compact('category'));
    }

    /**
     * عرض نموذج تعديل الفئة
     */
    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * تحديث فئة
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // رفع صورة الفئة الجديدة
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');

            // حذف الصورة القديمة
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            if ($imageFile instanceof \Illuminate\Http\UploadedFile) {
                $imagePath = $imageFile->store('categories', 'public');
                if ($imagePath !== false) {
                    $category->image = $imagePath;
                }
            }
        }

        $category->update([
            'name' => $request->input('name'),
            'name_ar' => $request->input('name_ar'),
            'description' => $request->input('description'),
            'sort_order' => $request->input('sort_order') ?? 0,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'تم تحديث الفئة بنجاح!');
    }

    /**
     * حذف الفئة
     */
    public function destroy(Category $category): RedirectResponse
    {
        try {
            // التحقق من عدم وجود منتجات مرتبطة
            if ($category->products()->count() > 0) {
                return redirect()->back()
                    ->withErrors(['error' => 'لا يمكن حذف الفئة لوجود منتجات مرتبطة بها!']);
            }

            $category->delete();

            return redirect()->route('categories.index')
                ->with('success', 'تم حذف الفئة بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء حذف الفئة: '.$e->getMessage()]);
        }
    }

    /**
     * البحث في الفئات
     */
    public function search(Request $request): View
    {
        $query = $request->get('q');
        $categories = Category::where('name', 'like', "%{$query}%")
            ->orWhere('name_ar', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orderBy('sort_order', 'asc')
            ->paginate(20);

        return view('categories.index', compact('categories', 'query'));
    }

    /**
     * تبديل حالة الفئة
     */
    public function toggleStatus(Category $category): RedirectResponse
    {
        try {
            $newStatus = $category->status === 'active' ? 'inactive' : 'active';
            $category->update(['status' => $newStatus]);

            $statusText = $newStatus === 'active' ? 'تفعيل' : 'إلغاء تفعيل';

            return redirect()->back()
                ->with('success', "تم {$statusText} الفئة بنجاح!");

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء تغيير حالة الفئة: '.$e->getMessage()]);
        }
    }
}

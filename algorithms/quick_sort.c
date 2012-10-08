void swap(int *v, int i, int j)
{
    int temp;
    temp = v[i];
    v[i] = v[j];
    v[j] = temp;
}
void quicksort(int *digits, int left, int right)
{
    int i, last;
    void swap(int *v, int m, int n);

    if (left >= right)
        return;

    swap(digits, left, (left + right)/2);
    last = left;
    for (i = left+1; i < right; i++)
        if (digits[i] < digits[left]) 
            swap(digits,++last, i);
    swap(digits, left, last);
   
    sorteddigits(digits, left, last-1);
    sorteddigits(digits, last+1, right);
}

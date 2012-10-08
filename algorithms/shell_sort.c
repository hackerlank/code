void shellsort(int *digits, int n)
{
    int gap, i, j, temp;

    for (gap = n/2; gap > 0; gap /= 2) 
        for (i = gap; i < n; i++) 
            for (j = i - gap; j >= 0 && digits[j] > digits[j+gap]; j -= gap) {
                temp = digits[j];
                digits[j] = digits[j+gap];
                digits[j+gap] = temp;
            }
}

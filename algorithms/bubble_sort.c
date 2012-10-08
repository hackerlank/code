void bubblesort(int *digits)
{
    int i,j,current_digit;

    for (i = 0; '\0' != digits[i]; i++)
        for (j=i+1; '\0' != digits[j]; j++) 
            if (digits[i] > digits[j]) {
                current_digit = digits[i];
                digits[i] = digits[j];
                digits[j] = current_digit;
            }
}

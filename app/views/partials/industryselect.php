<?php
  $industries = array(
    'Architecture/Design/Urban Planning',
    'Agriculture',
    'Communications/Marketing/Advertising/PR',
    'Computer Science/Technology',
    'Consulting',
    'Consumer Goods/Retail',
    'Education',
    'Entertainment/Professional Sports',
    'Environment',
    'Fashion',
    'Financial Services',
    'Fine or Performing Arts',
    'Healthcare',
    'Law/Legal Services',
    'Medical/Pharmaceutical',
    'Non-Profit',
    'Public Policy/Politics',
    'Publishing/Media/Journalism',
    'Real Estate',
    'Research'
  );
?>

<left style="margin-left: 1em;">
  <?php 
    $i = 1;
    foreach ($industries as $industry) {
  ?>
    <input type="checkbox" name="industry[]" id="industry<?php echo $i; ?>" value="<?php echo $industry; ?>" <?php vchecked('industry', $industry); ?> />
    <label style="display: inline-block" for="industry<?php echo $i; ?>"><?php echo $industry; ?></label><br />
  <?php
      $i ++;
    }
  ?>
</left>
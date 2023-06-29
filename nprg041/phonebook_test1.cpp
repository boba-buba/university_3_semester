#include "phonebook.hpp"

#include <iostream>

void dump( const phonebook & s, const phonebook::person_handle_list & b)
{
	for ( auto it = b.begin(); it != b.end(); ++ it)
	{
		phonebook::person_handle h = * it;
		const phonebook::person_entry & a = s.get_person_entry( h);
		std::cout << a.first_name << " " << a.last_name << " " << a.dept;
		for ( auto it2 = a.phones.begin();
			it2 != a.phones.end();
			++ it2)
		{
			std::cout << " " << * it2;
		}
		std::cout << std::endl;
	}
}


int main( int argc, char * * argv)
{
	phonebook s;

	{
		phonebook::person_entry z;

		z.first_name = "Melinda";
		z.last_name = "French";
		z.dept = "Marketing";
		z.phones.push_back( "4271");

		phonebook::person_handle h2 = s.add_person( z);
		s.add_phone(h2, "4323");
	}

	phonebook::person_handle_list b = s.find( 
		phonebook::dept_equal, "Marketing", 
		phonebook::last_name_prefix, "Fr");

	dump( s, b);

	if (b.begin() != b.end())
	{
		phonebook::person_handle h = *b.begin();
		s.add_phone(h, "4111");
		s.remove_phone(h, "4323");
		s.change_last_name(h, "Gates");
	}
	else
	{
		std::cout << "Not found" << std::endl;
	}

	phonebook::person_handle_list b2 = s.find( 
		phonebook::dept_equal, "Marketing", 
		phonebook::any_last_name, "");

	dump( s, b2);

	if (b.begin() != b.end())
	{
		phonebook::person_handle h = *b.begin();
		s.remove_person(h);
	}

	return 0;
}